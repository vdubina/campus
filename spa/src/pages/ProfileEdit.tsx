import { useState } from "react";
import { motion } from "framer-motion";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { ArrowLeft } from "lucide-react";
import type { StudentProfile } from "@/types/student";

interface ProfileEditProps {
  student: StudentProfile;
  onSave: (payload: {
    first_name: string;
    last_name: string;
    email: string;
    phone: string | null;
  }) => Promise<void>;
  onBack: () => void;
}

const ProfileEdit = ({ student, onSave, onBack }: ProfileEditProps) => {
  const initials = `${student.last_name[0] ?? ""}${student.first_name[0] ?? ""}`.toUpperCase();
  const [form, setForm] = useState({
    lastName: student.last_name,
    firstName: student.first_name,
    email: student.email,
    phone: student.phone ?? "",
  });
  const [error, setError] = useState("");
  const [isSaving, setIsSaving] = useState(false);

  const handleChange = (field: string, value: string) => {
    setForm((prev) => ({ ...prev, [field]: value }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError("");
    setIsSaving(true);

    try {
      await onSave({
        first_name: form.firstName,
        last_name: form.lastName,
        email: form.email,
        phone: form.phone.trim() === "" ? null : form.phone.trim(),
      });
      onBack();
    } catch (err) {
      setError(err instanceof Error ? err.message : "Не вдалося оновити профіль.");
    } finally {
      setIsSaving(false);
    }
  };

  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      exit={{ opacity: 0, y: -20 }}
      transition={{ duration: 0.3 }}
      className="w-full max-w-md mx-auto px-4"
    >
      <Card className="border-border/50 bg-card/80 backdrop-blur-sm">
        <CardHeader className="pb-4">
          <div className="flex items-center gap-3">
            <Button variant="ghost" size="icon" onClick={onBack} className="shrink-0">
              <ArrowLeft className="w-4 h-4" />
            </Button>
            <CardTitle className="text-lg">Редагування профілю</CardTitle>
          </div>
        </CardHeader>
        <CardContent className="space-y-6">
          <div className="flex justify-center">
            <Avatar className="w-20 h-20">
              <AvatarImage
                src={`https://api.dicebear.com/9.x/initials/svg?seed=${initials}&backgroundColor=c0392b`}
                alt="Аватар"
              />
              <AvatarFallback className="bg-primary/20 text-primary text-xl font-bold">
                {initials}
              </AvatarFallback>
            </Avatar>
          </div>

          <form className="space-y-4" onSubmit={handleSubmit}>
            <div className="space-y-2">
              <Label htmlFor="lastName">Прізвище</Label>
              <Input
                id="lastName"
                value={form.lastName}
                onChange={(e) => handleChange("lastName", e.target.value)}
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="firstName">Ім'я</Label>
              <Input
                id="firstName"
                value={form.firstName}
                onChange={(e) => handleChange("firstName", e.target.value)}
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="email">Email</Label>
              <Input
                id="email"
                type="email"
                value={form.email}
                onChange={(e) => handleChange("email", e.target.value)}
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="phone">Телефон</Label>
              <Input
                id="phone"
                value={form.phone}
                onChange={(e) => handleChange("phone", e.target.value)}
              />
            </div>
            <div className="space-y-2">
              <Label>Роль</Label>
              <Input value="Студент" disabled className="opacity-60" />
            </div>
            {error && <p className="text-sm text-destructive">{error}</p>}

            <Button className="w-full" type="submit" disabled={isSaving}>
              {isSaving ? "Збереження..." : "Зберегти"}
            </Button>
          </form>
        </CardContent>
      </Card>
    </motion.div>
  );
};

export default ProfileEdit;
