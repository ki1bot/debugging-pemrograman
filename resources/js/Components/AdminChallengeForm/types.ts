import { AdminChallenge, Category, Difficulty } from "@/types";
import { useForm } from "@inertiajs/react";

export type HintForm = {
    content: string;
    point_penalty: number;
};

export type SolutionForm = {
    solution_code: string;
    solution_type: "primary" | "alternative";
    required_keywords: string[];
};

export type ChallengeFormData = {
    category_id: number;
    difficulty_id: number;
    title: string;
    slug: string;
    description: string;
    broken_code: string;
    buggy_line: number;
    explanation: string;
    base_points: number;
    status: "draft" | "published" | "inactive";
    hints: HintForm[];
    solutions: SolutionForm[];
};

export type EditableChallenge = AdminChallenge & {
    category_id?: number;
    difficulty_id?: number;
    hints?: HintForm[];
    solutions?: SolutionForm[];
};

export type AdminChallengeFormProps = {
    categories: Category[];
    difficulties: Difficulty[];
    challenge?: EditableChallenge;
};

export type ChallengeFormErrors = Record<string, string>;

export type AdminChallengeFormState = ReturnType<
    typeof useForm<ChallengeFormData>
>;

export type SetChallengeFormData = AdminChallengeFormState["setData"];
