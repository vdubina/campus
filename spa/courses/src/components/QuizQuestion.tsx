import { motion } from "framer-motion";
import { ChevronLeft, ChevronRight } from "lucide-react";
import type { Question } from "@/data/quizData";

interface QuizQuestionProps {
  question: Question;
  currentIndex: number;
  totalQuestions: number;
  selectedOption: number | null;
  onSelectOption: (index: number) => void;
  onNext: () => void;
  onPrev: () => void;
}

const optionLabels = ["A", "B", "C", "D"];

const QuizQuestion = ({
  question,
  currentIndex,
  totalQuestions,
  selectedOption,
  onSelectOption,
  onNext,
  onPrev,
}: QuizQuestionProps) => {
  return (
    <motion.div
      key={question.id}
      initial={{ opacity: 0, x: 40 }}
      animate={{ opacity: 1, x: 0 }}
      exit={{ opacity: 0, x: -40 }}
      transition={{ duration: 0.35 }}
      className="w-full max-w-2xl mx-auto px-4"
    >
      {/* Progress */}
      <div className="mb-8">
        <div className="flex items-center justify-between mb-3">
          <span className="text-sm font-mono text-muted-foreground">
            Питання {currentIndex + 1}/{totalQuestions}
          </span>
          <span className="text-sm font-mono text-primary">
            {Math.round(((currentIndex + 1) / totalQuestions) * 100)}%
          </span>
        </div>
        <div className="h-1.5 rounded-full bg-secondary overflow-hidden">
          <motion.div
            className="h-full rounded-full bg-primary"
            initial={{ width: `${(currentIndex / totalQuestions) * 100}%` }}
            animate={{
              width: `${((currentIndex + 1) / totalQuestions) * 100}%`,
            }}
            transition={{ duration: 0.4 }}
          />
        </div>
      </div>

      {/* Question */}
      <h2 className="text-xl md:text-2xl font-bold mb-8 leading-relaxed">
        {question.text}
      </h2>

      {/* Options */}
      <div className="space-y-3 mb-10">
        {question.options.map((option, index) => {
          const isSelected = selectedOption === index;
          return (
            <motion.button
              key={index}
              whileHover={{ scale: 1.01 }}
              whileTap={{ scale: 0.99 }}
              onClick={() => onSelectOption(index)}
              className={`w-full text-left p-4 rounded-xl border transition-all duration-200 flex items-start gap-4 ${
                isSelected
                  ? "border-primary/60 bg-primary/10 glow-border"
                  : "border-border bg-card hover:border-muted-foreground/30 hover:bg-secondary/50"
              }`}
            >
              <span
                className={`shrink-0 w-8 h-8 rounded-lg flex items-center justify-center text-sm font-mono font-bold transition-colors ${
                  isSelected
                    ? "bg-primary text-primary-foreground"
                    : "bg-secondary text-muted-foreground"
                }`}
              >
                {optionLabels[index]}
              </span>
              <span className="pt-1 text-sm md:text-base">{option}</span>
            </motion.button>
          );
        })}
      </div>

      {/* Navigation */}
      <div className="flex items-center justify-between">
        <button
          onClick={onPrev}
          disabled={currentIndex === 0}
          className="flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
        >
          <ChevronLeft className="w-4 h-4" />
          Назад
        </button>
        <button
          onClick={onNext}
          disabled={selectedOption === null}
          className="flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold bg-primary text-primary-foreground hover:brightness-110 disabled:opacity-30 disabled:cursor-not-allowed transition-all glow-border hover:glow-border-active"
        >
          {currentIndex === totalQuestions - 1 ? "Завершити" : "Далі"}
          <ChevronRight className="w-4 h-4" />
        </button>
      </div>
    </motion.div>
  );
};

export default QuizQuestion;
