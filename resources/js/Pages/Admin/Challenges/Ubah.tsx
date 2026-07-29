import AdminChallengeForm from "@/Components/FormulirTantanganAdmin";
import AdminLayout from "@/Layouts/TataLetakAdmin";
import { AdminChallenge, Category, Difficulty } from "@/types";
import { Head } from "@inertiajs/react";

type HintForm = {
    content: string;
    point_penalty: number;
};

type SolutionForm = {
    solution_code: string;
    solution_type: "primary" | "alternative";
    required_keywords: string[];
};

type EditableChallenge = AdminChallenge & {
    category_id: number;
    difficulty_id: number;
    hints: HintForm[];
    solutions: SolutionForm[];
};

export default function EditChallenge({
    challenge,
    categories,
    difficulties,
}: {
    challenge: EditableChallenge;
    categories: Category[];
    difficulties: Difficulty[];
}) {
    return (
        <AdminLayout
            title={`Edit: ${challenge.title}`}
            description="Perubahan solusi dan hint akan mengganti data lama pada tantangan ini."
        >
            <Head title={`Edit ${challenge.title}`} />

            <AdminChallengeForm
                challenge={challenge}
                categories={categories}
                difficulties={difficulties}
            />
        </AdminLayout>
    );
}
