import EmptyState from "@/Components/EmptyState";
import Pagination from "@/Components/Pagination";
import StatusBadge from "@/Components/StatusBadge";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Category, Difficulty, Paginator, SubmissionStatus } from "@/types";
import { Head, Link } from "@inertiajs/react";

type HistorySubmission = {
    id: number;
    selected_line: number;
    line_score: number;
    code_score: number;
    explanation_score: number;
    hint_penalty: number;
    final_score: number;
    status: SubmissionStatus;
    completed_at?: string | null;
    created_at: string;
    challenge: {
        id: number;
        title: string;
        slug: string;
        base_points: number;
        category: Category;
        difficulty: Difficulty;
    };
};

type HistoryIndexProps = {
    submissions: Paginator<HistorySubmission>;
};

export default function HistoryIndex({ submissions }: HistoryIndexProps) {
    return (
        <AuthenticatedLayout
            header={
                <div>
                    <p className="text-sm font-black uppercase tracking-[0.18em]">
                        Jawaban yang Pernah Dikirim
                    </p>

                    <h1 className="mt-2 text-4xl font-black tracking-[-0.05em]">
                        Riwayat Latihan
                    </h1>
                </div>
            }
        >
            <Head title="Riwayat Latihan" />

            <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                {submissions.data.length > 0 ? (
                    <>
                        <div className="overflow-x-auto border-[3px] border-black bg-white shadow-[6px_6px_0_#111]">
                            <table className="nb-table min-w-[950px]">
                                <thead>
                                    <tr>
                                        <th>Tantangan</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Skor</th>
                                        <th>Penalti</th>
                                        <th>Dikirim</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    {submissions.data.map((submission) => (
                                        <tr key={submission.id}>
                                            <td>
                                                <p className="font-black">
                                                    {submission.challenge.title}
                                                </p>

                                                <p className="mt-1 text-xs font-bold text-neutral-600">
                                                    {
                                                        submission.challenge
                                                            .difficulty.name
                                                    }
                                                </p>
                                            </td>

                                            <td>
                                                <span className="nb-badge bg-[#9ed8ff]">
                                                    {
                                                        submission.challenge
                                                            .category.name
                                                    }
                                                </span>
                                            </td>

                                            <td>
                                                <StatusBadge
                                                    status={submission.status}
                                                />
                                            </td>

                                            <td>
                                                <strong>
                                                    {submission.final_score}/
                                                    {
                                                        submission.challenge
                                                            .base_points
                                                    }
                                                </strong>
                                            </td>

                                            <td>{submission.hint_penalty}%</td>

                                            <td>
                                                {new Date(
                                                    submission.created_at,
                                                ).toLocaleString("id-ID", {
                                                    dateStyle: "medium",
                                                    timeStyle: "short",
                                                })}
                                            </td>

                                            <td>
                                                <Link
                                                    href={route(
                                                        "submissions.show",
                                                        submission.id,
                                                    )}
                                                    className="nb-button bg-[#ffd93d] text-xs"
                                                >
                                                    Buka Hasil
                                                </Link>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        <Pagination links={submissions.links} />
                    </>
                ) : (
                    <EmptyState
                        title="Belum ada riwayat latihan"
                        description="Jawaban yang kamu kirim akan muncul di halaman ini."
                    />
                )}
            </div>
        </AuthenticatedLayout>
    );
}
