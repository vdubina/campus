import { useEffect, useMemo, useState } from "react";
import { AnimatePresence, motion } from "framer-motion";
import { Award, BookOpen, ClipboardList, GraduationCap } from "lucide-react";
import { useQuiz } from "@/hooks/useQuiz";
import QuizStart from "@/components/QuizStart";
import QuizQuestion from "@/components/QuizQuestion";
import QuizResults from "@/components/QuizResults";
import CourseSidebar, { getCourseIcon } from "@/components/CourseSidebar";
import UserProfile from "@/components/UserProfile";
import Login from "@/pages/Login";
import ProfileEdit from "@/pages/ProfileEdit";
import logo from "@/assets/logo.webp";
import { useIsMobile } from "@/hooks/use-mobile";
import { fetchCurrentUser, fetchStudentDashboard, getAccessToken, login, logout, updateProfile } from "@/lib/api";
import type { AuthUser, CourseDto, StudentDashboardResponse } from "@/types/student";

type Page = "dashboard" | "quiz" | "profile";

function formatDate(value: string | null): string {
  if (!value) {
    return "Немає";
  }

  return new Date(value).toLocaleDateString("uk-UA");
}

const Index = () => {
  const isMobile = useIsMobile();
  const [isLoadingAuth, setIsLoadingAuth] = useState(true);
  const [authUser, setAuthUser] = useState<AuthUser | null>(null);
  const [dashboard, setDashboard] = useState<StudentDashboardResponse | null>(null);
  const [page, setPage] = useState<Page>("dashboard");
  const [activeCourse, setActiveCourse] = useState<number | null>(null);

  const courses = dashboard?.courses ?? [];
  const selectedCourse = activeCourse === null ? null : courses[activeCourse] ?? null;
  const activeQuiz = selectedCourse?.quizzes[0] ?? null;
  const quizQuestions = useMemo(
    () =>
      (activeQuiz?.questions ?? [])
        .filter((question) => question.options.length > 0 && question.correct_answer_index !== null)
        .map((question) => ({
          id: question.id,
          text: question.text,
          options: question.options,
          correctAnswer: question.correct_answer_index as number,
        })),
    [activeQuiz]
  );
  const quiz = useQuiz(quizQuestions);

  const loadDashboard = async (): Promise<void> => {
    const payload = await fetchStudentDashboard();
    setDashboard(payload);
  };

  useEffect(() => {
    const bootstrap = async () => {
      const token = getAccessToken();

      if (!token) {
        setIsLoadingAuth(false);
        return;
      }

      try {
        const user = await fetchCurrentUser();
        setAuthUser(user);
        await loadDashboard();
      } catch {
        await logout();
        setAuthUser(null);
        setDashboard(null);
      } finally {
        setIsLoadingAuth(false);
      }
    };

    bootstrap();
  }, []);

  const handleLogin = async (email: string, password: string): Promise<void> => {
    const user = await login(email, password);

    if (!user.student) {
      throw new Error("Для цього користувача не знайдено студентський профіль.");
    }

    setAuthUser(user);
    await loadDashboard();
    setPage("dashboard");
  };

  const handleLogout = async (): Promise<void> => {
    await logout();
    setAuthUser(null);
    setDashboard(null);
    setPage("dashboard");
    setActiveCourse(null);
  };

  const handleSelectCourse = (index: number): void => {
    setActiveCourse(index);
    setPage("quiz");
  };

  const handleGoToDashboard = (): void => {
    setPage("dashboard");
    setActiveCourse(null);
    quiz.restartQuiz();
  };

  const handleSaveProfile = async (payload: {
    first_name: string;
    last_name: string;
    email: string;
    phone: string | null;
  }): Promise<void> => {
    const user = await updateProfile(payload);
    setAuthUser(user);
    await loadDashboard();
  };

  if (isLoadingAuth) {
    return (
      <div className="min-h-screen flex items-center justify-center text-muted-foreground">
        Завантаження...
      </div>
    );
  }

  if (!authUser || !authUser.student) {
    return <Login onLogin={handleLogin} />;
  }

  return (
    <div className="min-h-screen flex flex-col relative overflow-hidden">
      <div className="fixed inset-0 pointer-events-none" style={{ background: "var(--gradient-glow)" }} />

      <header className="relative z-10 flex items-center gap-3 px-6 py-4 border-b border-border/50">
        <button
          type="button"
          onClick={handleGoToDashboard}
          className="flex items-center gap-3 text-left hover:opacity-90 transition-opacity"
        >
          <img src={logo} alt="Logo" className="w-8 h-8 object-contain" />
          <span className="text-sm font-semibold tracking-wide">
            ЦЕНТР ПІДГОТОВКИ ОПЕРАТОРІВ БПЛА <span className="text-primary">«КРУК»</span>
          </span>
        </button>
        <div className="ml-auto">
          <UserProfile
            student={authUser.student}
            onLogout={handleLogout}
            onEditProfile={() => setPage("profile")}
            onGoToQuiz={() => setPage("quiz")}
          />
        </div>
      </header>

      <div className="relative z-10 flex flex-1">
        {page === "quiz" && <CourseSidebar courses={courses} activeIndex={activeCourse} onSelectCourse={handleSelectCourse} />}

        <main className="flex-1 flex flex-col items-center justify-center py-12">
          {page === "profile" ? (
            <ProfileEdit
              student={authUser.student}
              onSave={handleSaveProfile}
              onBack={() => setPage("dashboard")}
            />
          ) : page === "dashboard" ? (
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.3 }}
              className="w-full max-w-5xl px-4"
            >
              <div className="grid grid-cols-1 md:grid-cols-3 gap-3 mb-5">
                <div className="rounded-xl border border-border bg-card/70 p-4">
                  <p className="text-xs text-muted-foreground">Призначено курсів</p>
                  <p className="text-3xl font-bold mt-2">{courses.length}</p>
                </div>
                <div className="rounded-xl border border-border bg-card/70 p-4">
                  <p className="text-xs text-muted-foreground">Квізів доступно</p>
                  <p className="text-3xl font-bold mt-2">
                    {courses.reduce((sum, course) => sum + course.quizzes.length, 0)}
                  </p>
                </div>
                <div className="rounded-xl border border-border bg-card/70 p-4">
                  <p className="text-xs text-muted-foreground">Сертифікатів отримано</p>
                  <p className="text-3xl font-bold mt-2">{courses.filter((course) => course.certification).length}</p>
                </div>
              </div>

              <div className="rounded-xl border border-border bg-card/70 p-4">
                <div className="flex items-center justify-between mb-3">
                  <h2 className="text-lg font-semibold">Мої курси</h2>
                  <button
                    onClick={() => setPage("quiz")}
                    className="text-sm text-primary hover:brightness-110 transition"
                  >
                    До тестування
                  </button>
                </div>
                <div className="space-y-3">
                  {courses.map((course, index) => {
                    const Icon = getCourseIcon(index);

                    return (
                      <div key={course.enrollment_id} className="rounded-lg border border-border/70 bg-background/30 p-4">
                        <div className="flex items-start gap-3">
                          <Icon className="w-5 h-5 text-primary mt-0.5" />
                          <div className="flex-1">
                            <div className="flex items-start justify-between gap-3">
                              <h3 className="text-sm md:text-base font-semibold">{course.title}</h3>
                              <button
                                onClick={() => handleSelectCourse(index)}
                                className="text-xs px-2 py-1 rounded-md bg-primary/15 text-primary hover:bg-primary/20"
                              >
                                Пройти квіз
                              </button>
                            </div>
                            <p className="text-xs text-muted-foreground mt-1">{course.description ?? "Опис не додано."}</p>
                            <div className="flex flex-wrap items-center gap-3 text-xs mt-2 text-muted-foreground">
                              <span className="inline-flex items-center gap-1">
                                <BookOpen className="w-3.5 h-3.5" />
                                Тем: {course.topics.length}
                              </span>
                              <span className="inline-flex items-center gap-1">
                                <ClipboardList className="w-3.5 h-3.5" />
                                Квізів: {course.quizzes.length}
                              </span>
                              <span className="inline-flex items-center gap-1">
                                <GraduationCap className="w-3.5 h-3.5" />
                                Прогрес: {course.progress_percentage}%
                              </span>
                              <span>Статус: {course.status}</span>
                            </div>
                            {course.certification && (
                              <p className="text-xs mt-2 text-primary inline-flex items-center gap-1">
                                <Award className="w-3.5 h-3.5" />
                                Сертифікат {course.certification.certificate_number} ({formatDate(course.certification.issued_at)})
                              </p>
                            )}
                          </div>
                        </div>
                      </div>
                    );
                  })}
                </div>
              </div>
            </motion.div>
          ) : (
            <>
              {isMobile && activeCourse !== null && (
                <button
                  onClick={() => {
                    setActiveCourse(null);
                    setPage("dashboard");
                  }}
                  className="self-start ml-4 mb-4 text-xs text-muted-foreground hover:text-foreground transition-colors"
                >
                  ← Назад до кабінету
                </button>
              )}
              <AnimatePresence mode="wait">
                {activeCourse === null ? (
                  <div className="flex flex-col items-center text-center px-4 w-full max-w-2xl">
                    <h2 className="text-2xl font-bold mb-3 text-foreground">Оберіть курс для тестування</h2>
                    <p className="text-muted-foreground mb-6">
                      {isMobile ? "Оберіть курс нижче" : "Виберіть курс у меню зліва, щоб розпочати"}
                    </p>
                    {isMobile && (
                      <div className="grid grid-cols-1 gap-2 w-full px-2">
                        {courses.map((course, index) => {
                          const Icon = getCourseIcon(index);

                          return (
                            <motion.button
                              key={course.id}
                              initial={{ opacity: 0, y: 10 }}
                              animate={{ opacity: 1, y: 0 }}
                              transition={{ delay: index * 0.05 }}
                              onClick={() => handleSelectCourse(index)}
                              className="flex items-center gap-3 px-4 py-3 rounded-lg bg-card/50 border border-border/50 text-left hover:bg-primary/10 hover:border-primary/30 transition-colors"
                            >
                              <Icon className="w-5 h-5 shrink-0 text-primary" />
                              <span className="text-xs font-medium text-foreground leading-tight">{course.title}</span>
                            </motion.button>
                          );
                        })}
                      </div>
                    )}
                  </div>
                ) : quizQuestions.length === 0 ? (
                  <motion.div
                    key="no-quiz"
                    initial={{ opacity: 0, y: 10 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="text-center px-4"
                  >
                    <h3 className="text-xl font-semibold mb-2">Квіз ще недоступний</h3>
                    <p className="text-muted-foreground">Для цього курсу поки немає опублікованих запитань.</p>
                  </motion.div>
                ) : !quiz.isStarted ? (
                  <QuizStart
                    key="start"
                    onStart={quiz.startQuiz}
                    totalQuestions={quiz.totalQuestions}
                    courseTitle={selectedCourse?.title ?? "Курс"}
                    courseDescription={selectedCourse?.description ?? "Пройдіть тестування по цьому курсу"}
                  />
                ) : quiz.isFinished ? (
                  <QuizResults
                    key="results"
                    answers={quiz.answers}
                    score={quiz.score}
                    totalQuestions={quiz.totalQuestions}
                    onRestart={quiz.restartQuiz}
                    courseTitle={selectedCourse?.title ?? "Курс"}
                  />
                ) : quiz.question ? (
                  <QuizQuestion
                    key={`q-${quiz.currentQuestion}`}
                    question={quiz.question}
                    currentIndex={quiz.currentQuestion}
                    totalQuestions={quiz.totalQuestions}
                    selectedOption={quiz.selectedOption}
                    onSelectOption={quiz.selectOption}
                    onNext={quiz.nextQuestion}
                    onPrev={quiz.prevQuestion}
                  />
                ) : null}
              </AnimatePresence>
            </>
          )}
        </main>
      </div>
    </div>
  );
};

export default Index;
