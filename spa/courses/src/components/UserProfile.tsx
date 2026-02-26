import { Settings, LogOut, ClipboardList } from "lucide-react";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import type { StudentProfile } from "@/types/student";

interface UserProfileProps {
  student: StudentProfile;
  onLogout?: () => void;
  onEditProfile?: () => void;
  onGoToQuiz?: () => void;
}

const UserProfile = ({ student, onLogout, onEditProfile, onGoToQuiz }: UserProfileProps) => {
  const firstLetter = student.last_name[0] ?? "";
  const secondLetter = student.first_name[0] ?? "";
  const initials = `${firstLetter}${secondLetter}`.trim().toUpperCase();

  return (
    <DropdownMenu>
      <DropdownMenuTrigger asChild>
        <button className="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-muted/30 transition-colors">
          <Avatar className="w-8 h-8">
            <AvatarImage src={`https://api.dicebear.com/9.x/initials/svg?seed=${initials}&backgroundColor=c0392b`} alt="Аватар" />
            <AvatarFallback className="bg-primary/20 text-primary text-xs font-bold">{initials}</AvatarFallback>
          </Avatar>
          <span className="hidden sm:block text-sm font-medium">{student.full_name}</span>
        </button>
      </DropdownMenuTrigger>
      <DropdownMenuContent align="end" className="w-48">
        <div className="px-2 py-2">
          <p className="text-sm font-semibold">{student.full_name}</p>
          <p className="text-xs text-muted-foreground">Студент</p>
        </div>
        <DropdownMenuSeparator />
        <DropdownMenuItem className="cursor-pointer" onClick={onGoToQuiz}>
          <ClipboardList className="w-4 h-4 mr-2" />
          Тестування
        </DropdownMenuItem>
        <DropdownMenuItem className="cursor-pointer" onClick={onEditProfile}>
          <Settings className="w-4 h-4 mr-2" />
          Редагувати профіль
        </DropdownMenuItem>
        <DropdownMenuSeparator />
        <DropdownMenuItem className="cursor-pointer text-destructive focus:text-destructive" onClick={onLogout}>
          <LogOut className="w-4 h-4 mr-2" />
          Вийти
        </DropdownMenuItem>
      </DropdownMenuContent>
    </DropdownMenu>
  );
};

export default UserProfile;
