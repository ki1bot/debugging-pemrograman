import AdminChallengeForm from "@/Components/FormulirTantanganAdmin";
import AdminLayout from "@/Layouts/TataLetakAdmin";
import { Category, Difficulty } from "@/types";
import { Head } from "@inertiajs/react";

export default function CreateChallenge({
    categories,
    difficulties,
}: {
    categories: Category[];
    difficulties: Difficulty[];
}) {
    return (
        <AdminLayout
            title="Tambah Tantangan"
            description="Buat kode bermasalah, solusi, pembahasan, kata kunci, dan hint secara lengkap."
        >
            <Head title="Tambah Tantangan" />

            <AdminChallengeForm
                categories={categories}
                difficulties={difficulties}
            />
        </AdminLayout>
    );
}
