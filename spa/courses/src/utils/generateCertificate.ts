import jsPDF from "jspdf";

interface CertificateData {
  courseTitle: string;
  score: number;
  totalQuestions: number;
  percentage: number;
  date: string;
}

export function generateCertificate(data: CertificateData) {
  const doc = new jsPDF({ orientation: "landscape", unit: "mm", format: "a4" });
  const w = doc.internal.pageSize.getWidth();
  const h = doc.internal.pageSize.getHeight();

  // Background
  doc.setFillColor(15, 18, 25);
  doc.rect(0, 0, w, h, "F");

  // Border
  doc.setDrawColor(180, 40, 40);
  doc.setLineWidth(1.5);
  doc.rect(10, 10, w - 20, h - 20);
  doc.setDrawColor(180, 40, 40);
  doc.setLineWidth(0.3);
  doc.rect(14, 14, w - 28, h - 28);

  // Header
  doc.setTextColor(180, 40, 40);
  doc.setFontSize(14);
  doc.text("ЦЕНТР ПІДГОТОВКИ ОПЕРАТОРІВ БПЛА «КРУК»", w / 2, 35, { align: "center" });

  // Title
  doc.setTextColor(240, 240, 240);
  doc.setFontSize(36);
  doc.setFont("helvetica", "bold");
  doc.text("СЕРТИФІКАТ", w / 2, 58, { align: "center" });

  // Subtitle
  doc.setFontSize(12);
  doc.setFont("helvetica", "normal");
  doc.setTextColor(160, 160, 170);
  doc.text("Цим підтверджується успішне проходження сертифікаційного тесту", w / 2, 72, { align: "center" });

  // Course title
  doc.setFontSize(20);
  doc.setTextColor(240, 240, 240);
  doc.setFont("helvetica", "bold");
  doc.text(data.courseTitle, w / 2, 95, { align: "center" });

  // Score
  doc.setFontSize(14);
  doc.setTextColor(160, 160, 170);
  doc.setFont("helvetica", "normal");
  doc.text(`Результат: ${data.score} з ${data.totalQuestions} (${data.percentage}%)`, w / 2, 112, { align: "center" });

  // Date
  doc.setFontSize(11);
  doc.text(`Дата: ${data.date}`, w / 2, 125, { align: "center" });

  // Decorative line
  doc.setDrawColor(180, 40, 40);
  doc.setLineWidth(0.5);
  doc.line(w / 2 - 40, 135, w / 2 + 40, 135);

  // Footer
  doc.setFontSize(9);
  doc.setTextColor(100, 100, 110);
  doc.text("Документ згенеровано автоматично системою тестування", w / 2, 170, { align: "center" });

  doc.save(`certificate-${data.courseTitle.replace(/\s+/g, "-")}.pdf`);
}
