export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string | null;
    role: "user" | "admin";
    total_points: number;
}

export interface FlashMessages {
    success?: string | null;
    error?: string | null;
    info?: string | null;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User | null;
    };
    flash: FlashMessages;
};

export interface Category {
    id: number;
    name: string;
    slug: string;
    description?: string | null;
    is_active?: boolean;
    challenges_count?: number;
}

export interface Difficulty {
    id: number;
    name: string;
    slug: string;
    base_points: number;
    is_active?: boolean;
    challenges_count?: number;
}

export interface ChallengeCard {
    id: number;
    title: string;
    slug: string;
    description: string;
    base_points: number;
    category: Category;
    difficulty: Difficulty;
    progress?: {
        best_score: number;
        attempts_count: number;
        is_completed: boolean;
    } | null;
}

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface Paginator<T> {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number | null;
    last_page: number;
    last_page_url: string;
    links: PaginationLink[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number | null;
    total: number;
}

export interface ProgressCard {
    id: number;
    best_score: number;
    attempts_count: number;
    hints_used: number;
    is_completed: boolean;
    completed_at?: string | null;
    updated_at: string;
    challenge: ChallengeCard;
    submission_id?: number | null;
    latest_status?: SubmissionStatus | null;
    latest_score?: number | null;
}

export type SubmissionStatus = "incorrect" | "partially_correct" | "completed";

export interface ChallengeHint {
    id: number;
    hint_order: number;
    point_penalty: number;
    unlocked: boolean;
    content: string | null;
}

export interface ChallengeDetail extends ChallengeCard {
    broken_code: string;
    line_count: number;
    hints: ChallengeHint[];
}

export interface SubmissionResult {
    id: number;
    selected_line: number;
    submitted_code: string;
    submitted_explanation: string;
    line_score: number;
    code_score: number;
    explanation_score: number;
    hint_penalty: number;
    final_score: number;
    status: SubmissionStatus;
    completed_at?: string | null;
    attempts_count: number;
    challenge: ChallengeCard & {
        broken_code: string;
        buggy_line: number;
        explanation: string;
        primary_solution: string | null;
        alternative_solutions: string[];
    };
}

export interface AdminChallenge {
    id: number;
    title: string;
    slug: string;
    description: string;
    broken_code?: string;
    buggy_line?: number;
    explanation?: string;
    base_points: number;
    status: "draft" | "published" | "inactive";
    category: Category;
    difficulty: Difficulty;
    creator?: Pick<User, "id" | "name"> | null;
    submissions_count?: number;
    solutions_count?: number;
    hints_count?: number;
    created_at: string;
    updated_at: string;
}

export interface AdminSubmission {
    id: number;
    user_id: number;
    challenge_id: number;
    selected_line: number;
    submitted_code: string;
    submitted_explanation: string;
    line_score: number;
    code_score: number;
    explanation_score: number;
    hint_penalty: number;
    final_score: number;
    status: SubmissionStatus;
    completed_at?: string | null;
    created_at: string;
    updated_at: string;
    attempts_count?: number;
    user: User;
    challenge: AdminChallenge;
}

declare global {
    const route: {
        (): {
            current: (name?: string) => string | boolean | undefined;
        };
        (name: string, params?: unknown, absolute?: boolean): string;
    };
}
