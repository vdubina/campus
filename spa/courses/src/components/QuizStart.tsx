import { Lock, Terminal } from "lucide-react";
import { motion } from "framer-motion";
import logo from "@/assets/logo.webp";

interface QuizStartProps {
  onStart: () => void;
  totalQuestions: number;
  disabled?: boolean;
  courseTitle: string;
  courseDescription: string;
}

const QuizStart = ({ onStart, totalQuestions, disabled, courseTitle, courseDescription }: QuizStartProps) => {
  return (
    <motion.div
      initial={{ opacity: 0, y: 30 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.6 }}
      className="flex flex-col items-center text-center max-w-2xl mx-auto px-4"
    >
      <div className="relative mb-8">
        <div className="w-24 h-24 rounded-2xl card-gradient glow-border flex items-center justify-center p-3">
          <img src={logo} alt="Logo" className="w-full h-full object-contain" />
        </div>
        <div className="absolute -top-2 -right-2 w-8 h-8 rounded-lg bg-accent/20 flex items-center justify-center">
          <Lock className="w-4 h-4 text-accent" />
        </div>
      </div>

      <h1 className="text-3xl md:text-4xl font-black mb-4 tracking-tight">
        <span className="text-foreground">{courseTitle}</span>
      </h1>
      <p className="text-lg text-muted-foreground mb-2 font-medium">
        Сертифікаційний тест
      </p>
      <p className="text-muted-foreground mb-10 max-w-md">
        {courseDescription}
      </p>

      <div className="flex items-center gap-6 mb-10 text-sm text-muted-foreground">
        <div className="flex items-center gap-2">
          <Terminal className="w-4 h-4 text-primary" />
          <span>{totalQuestions} питань</span>
        </div>
        <div className="w-1 h-1 rounded-full bg-muted-foreground" />
        <span>~10 хвилин</span>
        <div className="w-1 h-1 rounded-full bg-muted-foreground" />
        <span>70% для сертифікату</span>
      </div>

      <button
        onClick={onStart}
        disabled={disabled}
        className="group relative px-10 py-4 rounded-xl font-semibold text-primary-foreground bg-primary hover:brightness-110 transition-all duration-300 glow-border hover:glow-border-active disabled:opacity-40 disabled:pointer-events-none disabled:glow-border-none"
      >
        Розпочати тестування
      </button>
      {disabled && (
        <p className="mt-4 text-sm text-muted-foreground">
          Тест для цього курсу ще в розробці
        </p>
      )}
    </motion.div>
  );
};

export default QuizStart;
