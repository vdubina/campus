import type { AuthUser, StudentDashboardResponse } from "@/types/student";

const TOKEN_KEY = "campus_crm_access_token";

let accessToken: string | null = localStorage.getItem(TOKEN_KEY);

interface ApiOptions extends RequestInit {
  authenticated?: boolean;
}

async function apiRequest<T>(url: string, options: ApiOptions = {}): Promise<T> {
  const { authenticated = false, headers, ...rest } = options;
  const requestHeaders = new Headers(headers ?? {});

  if (!requestHeaders.has("Content-Type") && rest.body) {
    requestHeaders.set("Content-Type", "application/json");
  }

  if (authenticated && accessToken) {
    requestHeaders.set("Authorization", `Bearer ${accessToken}`);
  }

  const response = await fetch(url, {
    ...rest,
    headers: requestHeaders,
  });

  if (!response.ok) {
    const payload = await response.json().catch(() => ({} as Record<string, unknown>));
    const validationErrors =
      payload && typeof payload === "object" && "errors" in payload && payload.errors
        ? (payload.errors as Record<string, string[]>)
        : null;
    const validationMessage = validationErrors
      ? Object.values(validationErrors).flat().find((message) => typeof message === "string")
      : null;
    const message =
      validationMessage ??
      (typeof payload.message === "string" ? payload.message : "Помилка запиту.");
    throw new Error(message);
  }

  return response.json() as Promise<T>;
}

export function setAccessToken(token: string | null): void {
  accessToken = token;

  if (token) {
    localStorage.setItem(TOKEN_KEY, token);
  } else {
    localStorage.removeItem(TOKEN_KEY);
  }
}

export function getAccessToken(): string | null {
  return accessToken;
}

export async function login(email: string, password: string): Promise<AuthUser> {
  const response = await apiRequest<{ access_token: string; user: AuthUser }>("/api/auth/login", {
    method: "POST",
    body: JSON.stringify({
      email,
      password,
      device_name: "campus-crm-student-spa",
    }),
  });

  setAccessToken(response.access_token);

  return response.user;
}

export async function fetchCurrentUser(): Promise<AuthUser> {
  const response = await apiRequest<{ user: AuthUser }>("/api/auth/me", {
    authenticated: true,
  });

  return response.user;
}

export async function updateProfile(payload: {
  first_name: string;
  last_name: string;
  email: string;
  phone?: string | null;
}): Promise<AuthUser> {
  const response = await apiRequest<{ message: string; user: AuthUser }>("/api/auth/profile", {
    method: "PUT",
    authenticated: true,
    body: JSON.stringify(payload),
  });

  return response.user;
}

export async function logout(): Promise<void> {
  try {
    await apiRequest<{ message: string }>("/api/auth/logout", {
      method: "POST",
      authenticated: true,
    });
  } finally {
    setAccessToken(null);
  }
}

export async function fetchStudentDashboard(): Promise<StudentDashboardResponse> {
  return apiRequest<StudentDashboardResponse>("/api/student/dashboard", {
    authenticated: true,
  });
}
