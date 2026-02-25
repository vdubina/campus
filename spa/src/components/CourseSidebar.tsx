import { useState } from "react";
import {
  Cpu,
  Plane,
  Gamepad2,
  Map,
  Radio,
  Satellite,
  Wifi,
  ShieldCheck,
  Search,
  ChevronLeft,
  ChevronRight,
  type LucideIcon,
} from "lucide-react";
import type { CourseDto } from "@/types/student";

const iconSet: LucideIcon[] = [
  ShieldCheck,
  Cpu,
  Plane,
  Gamepad2,
  Map,
  Radio,
  Satellite,
  Wifi,
  Search,
];

interface CourseSidebarProps {
  courses: CourseDto[];
  activeIndex: number | null;
  onSelectCourse: (index: number) => void;
}

export function getCourseIcon(index: number): LucideIcon {
  return iconSet[index % iconSet.length] ?? ShieldCheck;
}

const CourseSidebar = ({ courses, activeIndex, onSelectCourse }: CourseSidebarProps) => {
  const [collapsed, setCollapsed] = useState(false);

  return (
    <aside
      className={`relative hidden md:flex flex-col border-r border-border/50 bg-card/30 backdrop-blur-sm transition-all duration-300 ${
        collapsed ? "w-16" : "w-72"
      }`}
    >
      {/* Toggle */}
      <button
        onClick={() => setCollapsed(!collapsed)}
        className="absolute -right-3 top-4 z-20 w-6 h-6 rounded-full bg-primary text-primary-foreground flex items-center justify-center hover:brightness-110 transition-all shadow-md"
      >
        {collapsed ? <ChevronRight className="w-3.5 h-3.5" /> : <ChevronLeft className="w-3.5 h-3.5" />}
      </button>

      {/* Header */}
      {!collapsed && (
        <div className="px-4 py-3 border-b border-border/50">
          <p className="text-xs font-semibold text-muted-foreground uppercase tracking-wider">ТЕСТУВАННЯ</p>
        </div>
      )}

      {/* List */}
      <nav className="flex-1 overflow-y-auto py-2">
        {courses.map((course, index) => {
          const Icon = getCourseIcon(index);

          return (
            <button
              key={course.title}
              onClick={() => onSelectCourse(index)}
              className={`w-full flex items-center gap-3 px-4 py-3 text-left text-xs font-medium transition-colors ${
                index === activeIndex
                  ? "bg-primary/15 text-primary border-r-2 border-primary"
                  : "text-muted-foreground hover:bg-muted/30 hover:text-foreground"
              } ${collapsed ? "justify-center px-0" : ""}`}
              title={course.title}
            >
              <Icon className={`shrink-0 ${collapsed ? "w-5 h-5" : "w-4 h-4"}`} />
              {!collapsed && <span className="leading-tight">{course.title}</span>}
            </button>
          );
        })}
      </nav>
    </aside>
  );
};

export default CourseSidebar;
