import CodeEditor from "@/Components/CodeEditor";
import StatusBadge from "@/Components/StatusBadge";
import AdminLayout from "@/Layouts/AdminLayout";
import { SubmissionStatus, User } from "@/types";
import { Head, Link } from "@inertiajs/react";

type SubmissionAttempt = {
    id: number;
    attempt_number: number;
    line_correct: boolean;
    code_correct: boolean;
    matched_keywords?: string[] | null;
    missing_keywords?: string[] | null;
    score_snapshot: number;
    status_snapshot: SubmissionStatus;
    created_at: string;
};

type SubmissionSolution = {
    id: number;
    solution_code: string;
    solution_type: "primary" | "alternative";
    required_keywords?: string[] | null;
};

type AdminSubmissionDetail = {
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
    created_at: string;
    user: User;
    attempts: SubmissionAttempt[];
    challenge: {
        id: number;
        title: string;
        slug: string;
        broken_code: string;
        buggy_line: number;
        explanation: string;
        base_points: number;
        category: {
            id: number;
            name: string;
            slug: string;
        };
        difficulty: {
            id: number;
            name: string;
            slug: string;
        };
        solutions: SubmissionSolution[];
    };
};

export default function SubmissionShow({
    submission,
}: {
    submission: AdminSubmissionDetail;
}) {
    const primarySolution = submission.challenge.solutions.find(
        (solution) => solution.solution_type === "primary",
    );

    return (
        <AdminLayout
            title={`Submission #${submission.id}`}
            description={`${submission.user.name} — ${submission.challenge.title}`}
        >
            <Head title={`Submission #${submission.id}`} />

            <section className="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <article className="nb-card bg-[#ff9c9c] p-5">
                    <p className="text-xs font-black uppercase">Skor Baris</p>
                    <p className="mt-3 text-4xl font-black">
                        {submission.line_score}
                    </p>
                </article>

                <article className="nb-card bg-[#9ed8ff] p-5">
                    <p className="text-xs font-black uppercase">Skor Kode</p>
                    <p className="mt-3 text-4xl font-black">
                        {submission.code_score}
                    </p>
                </article>

                <article className="nb-card bg-[#b7a4ff] p-5">
                    <p className="text-xs font-black uppercase">
                        Skor Penjelasan
                    </p>
                    <p className="mt-3 text-4xl font-black">
                        {submission.explanation_score}
                    </p>
                </article>

                <article className="nb-card bg-[#ffd93d] p-5">
                    <p className="text-xs font-black uppercase">Skor Akhir</p>
                    <p className="mt-3 text-4xl font-black">
                        {submission.final_score}
                    </p>
                </article>
            </section>

            <section className="mt-8 grid gap-8 xl:grid-cols-2">
                <article className="nb-card bg-white p-6">
                    <div className="flex flex-wrap items-center justify-between gap-4">
                        <h2 className="text-2xl font-black">
                            Jawaban Pengguna
                        </h2>

                        <StatusBadge status={submission.status} />
                    </div>

                    <div className="mt-6">
                        <CodeEditor
                            value={submission.submitted_code}
                            language={submission.challenge.category.slug}
                            readOnly
                            minHeight="400px"
                        />
                    </div>

                    <div className="mt-6 border-[3px] border-black bg-[#fff1a8] p-4">
                        <p className="text-sm font-black uppercase">
                            Baris Dipilih
                        </p>

                        <p className="mt-2 text-2xl font-black">
                            {submission.selected_line}
                        </p>
                    </div>
                </article>

                <article className="nb-card bg-[#9ef0b8] p-6">
                    <h2 className="text-2xl font-black">Solusi Utama</h2>

                    <div className="mt-6">
                        <CodeEditor
                            value={
                                primarySolution?.solution_code ??
                                "Solusi utama tidak tersedia."
                            }
                            language={submission.challenge.category.slug}
                            readOnly
                            minHeight="400px"
                        />
                    </div>

                    <div className="mt-6 border-[3px] border-black bg-white p-4">
                        <p className="text-sm font-black uppercase">
                            Baris Bug Benar
                        </p>

                        <p className="mt-2 text-2xl font-black">
                            {submission.challenge.buggy_line}
                        </p>
                    </div>
                </article>
            </section>

            <section className="mt-8 grid gap-8 xl:grid-cols-2">
                <article className="nb-card bg-[#fff1a8] p-6">
                    <h2 className="text-2xl font-black">Penjelasan Pengguna</h2>

                    <p className="mt-5 whitespace-pre-wrap font-semibold leading-8">
                        {submission.submitted_explanation}
                    </p>
                </article>

                <article className="nb-card bg-[#9ed8ff] p-6">
                    <h2 className="text-2xl font-black">
                        Pembahasan Administrator
                    </h2>

                    <p className="mt-5 whitespace-pre-wrap font-semibold leading-8">
                        {submission.challenge.explanation}
                    </p>
                </article>
            </section>

            <section className="nb-card mt-8 bg-white p-6">
                <h2 className="text-2xl font-black">Snapshot Penilaian</h2>

                <div className="mt-6 overflow-x-auto">
                    <table className="nb-table min-w-[800px]">
                        <thead>
                            <tr>
                                <th>Percobaan</th>
                                <th>Baris Benar</th>
                                <th>Kode Benar</th>
                                <th>Kata Kunci Cocok</th>
                                <th>Kata Kunci Kurang</th>
                                <th>Skor</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            {submission.attempts.map((attempt) => (
                                <tr key={attempt.id}>
                                    <td className="font-black">
                                        {attempt.attempt_number}
                                    </td>

                                    <td>
                                        {attempt.line_correct ? "Ya" : "Tidak"}
                                    </td>

                                    <td>
                                        {attempt.code_correct ? "Ya" : "Tidak"}
                                    </td>

                                    <td>
                                        {attempt.matched_keywords?.join(", ") ||
                                            "-"}
                                    </td>

                                    <td>
                                        {attempt.missing_keywords?.join(", ") ||
                                            "-"}
                                    </td>

                                    <td className="font-black">
                                        {attempt.score_snapshot}
                                    </td>

                                    <td>
                                        <StatusBadge
                                            status={attempt.status_snapshot}
                                        />
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </section>

            <div className="mt-8 flex flex-wrap justify-end gap-4">
                <Link
                    href={route("admin.submissions.index")}
                    className="nb-button bg-white"
                >
                    Kembali
                </Link>

                <Link
                    href={route(
                        "admin.challenges.edit",
                        submission.challenge.slug,
                    )}
                    className="nb-button bg-[#ffd93d]"
                >
                    Edit Tantangan
                </Link>
            </div>
        </AdminLayout>
    );
}
