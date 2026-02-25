import { motion } from "framer-motion";
import { CheckCircle2, XCircle, RotateCcw, Award, Download } from "lucide-react";
import { quizQuestions } from "@/data/quizData";
import { generateCertificate } from "@/utils/generateCertificate";

interface QuizResultsProps {
  answers: (number | null)[];
  score: number;
  totalQuestions: number;
  onRestart: () => void;
  courseTitle: string;
}

const QuizResults = ({
  answers,
  score,
  totalQuestions,
  onRestart,
  courseTitle,
}: QuizResultsProps) => {
  const percentage = Math.round((score / totalQuestions) * 100);
  const passed = percentage >= 70;

  return (
    <motion.div
      initial={{ opacity: 0, y: 30 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.6 }}
      className="w-full max-w-2xl mx-auto px-4"
    >
      {/* Score card */}
      <div className="text-center mb-10">
        <div
          className={`w-28 h-28 rounded-2xl mx-auto mb-6 flex items-center justify-center card-gradient ${
            passed ? "glow-border" : ""
          }`}
        >
          <Award
            className={`w-14 h-14 ${passed ? "text-primary" : "text-muted-foreground"}`}
          />
        </div>

        <h2 className="text-3xl font-black mb-2">
          {passed ? (
            <span className="text-gradient">Вітаємо!</span>
          ) : (
            "Спробуйте ще раз"
          )}
        </h2>
        <p className="text-muted-foreground mb-6">
          {passed
            ? "Ви успішно пройшли сертифікаційний тест"
            : "Для отримання сертифікату потрібно набрати щонайменше 70%"}
        </p>

        <div className="inline-flex items-center gap-4 px-6 py-3 rounded-xl bg-card border border-border">
          <span className="text-4xl font-black font-mono text-gradient">
            {percentage}%
          </span>
          <div className="text-left text-sm">
            <p className="text-foreground font-medium">
              {score} з {totalQuestions}
            </p>
            <p className="text-muted-foreground">правильних відповідей</p>
          </div>
        </div>
      </div>

      {/* Answers review */}
      <div className="space-y-3 mb-10">
        <h3 className="text-sm font-semibold text-muted-foreground uppercase tracking-wider mb-4">
          Огляд відповідей
        </h3>
        {quizQuestions.map((q, index) => {
          const userAnswer = answers[index];
          const isCorrect = userAnswer === q.correctAnswer;
          return (
            <div
              key={q.id}
              className={`p-4 rounded-xl border ${
                isCorrect
                  ? "border-success/30 bg-success/5"
                  : "border-destructive/30 bg-destructive/5"
              }`}
            >
              <div className="flex items-start gap-3">
                {isCorrect ? (
                  <CheckCircle2 className="w-5 h-5 text-success shrink-0 mt-0.5" />
                ) : (
                  <XCircle className="w-5 h-5 text-destructive shrink-0 mt-0.5" />
                )}
                <div className="min-w-0">
                  <p className="text-sm font-medium mb-1">{q.text}</p>
                  {!isCorrect && (
                    <p className="text-xs text-muted-foreground">
                      Правильна відповідь:{" "}
                      <span className="text-success">
                        {q.options[q.correctAnswer]}
                      </span>
                    </p>
                  )}
                </div>
              </div>
            </div>
          );
        })}
      </div>

      <div className="text-center space-y-3">
        {passed && (
          <button
            onClick={() =>
              generateCertificate({
                courseTitle,
                score,
                totalQuestions,
                percentage,
                date: new Date().toLocaleDateString("uk-UA"),
              })
            }
            className="inline-flex items-center gap-2 px-8 py-3 rounded-xl font-semibold text-primary-foreground transition-colors"
            style={{ backgroundImage: "var(--gradient-primary)" }}
          >
            <Download className="w-4 h-4" />
            Завантажити сертифікат (PDF)
          </button>
        )}
        <br />
        <button
          onClick={onRestart}
          className="inline-flex items-center gap-2 px-8 py-3 rounded-xl font-semibold bg-secondary text-secondary-foreground hover:bg-secondary/80 transition-colors"
        >
          <RotateCcw className="w-4 h-4" />
          Пройти ще раз
        </button>
      </div>
    </motion.div>
  );
};

export default QuizResults;
