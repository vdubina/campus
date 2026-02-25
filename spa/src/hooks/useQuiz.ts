import { useState, useCallback, useEffect } from "react";
import { quizQuestions } from "@/data/quizData";
import type { Question } from "@/data/quizData";

export function useQuiz(sourceQuestions: Question[] = quizQuestions) {
  const questions = sourceQuestions.length > 0 ? sourceQuestions : quizQuestions;
  const [currentQuestion, setCurrentQuestion] = useState(0);
  const [answers, setAnswers] = useState<(number | null)[]>(
    new Array(questions.length).fill(null)
  );
  const [selectedOption, setSelectedOption] = useState<number | null>(null);
  const [isFinished, setIsFinished] = useState(false);
  const [isStarted, setIsStarted] = useState(false);

  const totalQuestions = questions.length;
  const question = questions[currentQuestion] ?? null;

  const selectOption = useCallback((index: number) => {
    setSelectedOption(index);
  }, []);

  const nextQuestion = useCallback(() => {
    if (selectedOption === null) return;
    const newAnswers = [...answers];
    newAnswers[currentQuestion] = selectedOption;
    setAnswers(newAnswers);

    if (currentQuestion < totalQuestions - 1) {
      setCurrentQuestion((prev) => prev + 1);
      setSelectedOption(null);
    } else {
      setAnswers(newAnswers);
      setIsFinished(true);
    }
  }, [selectedOption, answers, currentQuestion, totalQuestions]);

  const prevQuestion = useCallback(() => {
    if (currentQuestion > 0) {
      setCurrentQuestion((prev) => prev - 1);
      setSelectedOption(answers[currentQuestion - 1]);
    }
  }, [currentQuestion, answers]);

  const startQuiz = useCallback(() => {
    setIsStarted(true);
  }, []);

  const restartQuiz = useCallback(() => {
    setCurrentQuestion(0);
    setAnswers(new Array(totalQuestions).fill(null));
    setSelectedOption(null);
    setIsFinished(false);
    setIsStarted(false);
  }, [totalQuestions]);

  useEffect(() => {
    restartQuiz();
  }, [restartQuiz]);

  const score = answers.reduce((acc, answer, index) => {
    return acc + (answer === questions[index]?.correctAnswer ? 1 : 0);
  }, 0);

  return {
    currentQuestion,
    question,
    totalQuestions,
    selectedOption,
    isFinished,
    isStarted,
    answers,
    score,
    selectOption,
    nextQuestion,
    prevQuestion,
    startQuiz,
    restartQuiz,
  };
}
