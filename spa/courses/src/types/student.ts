export interface StudentProfile {
  id: number;
  first_name: string;
  last_name: string;
  full_name: string;
  email: string;
  phone: string | null;
  external_id: string | null;
}

export interface AuthUser {
  id: number;
  name: string;
  email: string;
  roles: string[];
  student: StudentProfile | null;
}

export interface QuizQuestionDto {
  id: number;
  text: string;
  options: string[];
  correct_answer_index: number | null;
}

export interface QuizDto {
  id: number;
  title: string;
  description: string | null;
  passing_score: number;
  time_limit_minutes: number | null;
  max_attempts: number;
  is_published: boolean;
  questions: QuizQuestionDto[];
}

export interface TopicDto {
  id: number;
  title: string;
  description: string | null;
  position: number;
}

export interface CertificationDto {
  id: number;
  certificate_number: string;
  issued_at: string | null;
  status: string;
}

export interface CourseDto {
  enrollment_id: number;
  id: number;
  title: string;
  description: string | null;
  status: string;
  progress_percentage: number;
  enrolled_at: string | null;
  completed_at: string | null;
  instructor: {
    full_name: string;
    email: string;
  } | null;
  topics: TopicDto[];
  quizzes: QuizDto[];
  certification: CertificationDto | null;
}

export interface StudentDashboardResponse {
  student: StudentProfile;
  courses: CourseDto[];
}
