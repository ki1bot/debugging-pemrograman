import Pagination from "@/Components/Paginasi";
import StatusBadge from "@/Components/LencanaStatus";
import AdminLayout from "@/Layouts/TataLetakAdmin";
import { AdminSubmission, Paginator } from "@/types";
import { Head, Link, router } from "@inertiajs/react";
import { FormEvent, useState } from "react";

type ChallengeOption = {
    id: number;
    title: string;
};

type SubmissionIndexProps = {
    submissions: Paginator<AdminSubmission>;
    challenges: ChallengeOption[];
    filters: {
        search: string;
        status: string;
        challenge: string | number;
    };
};

export default function SubmissionIndex({
    submissions,
    challenges,
    filters,
}: SubmissionIndexProps) {
    const [search, setSearch] = useState(filters.search);

    const [status, setStatus] = useState(filters.status);

    const [challenge, setChallenge] = useState(String(filters.challenge ?? ""));

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        router.get(
            route("admin.submissions.index"),
            {
                search: search || undefined,
                status: status || undefined,
                challenge: challenge || undefined,
            },
            {
                preserveState: true,
                replace: true,
            },
        );
    };

    return (
        <AdminLayout
            title="Data Submission"
            description="Tinjau seluruh jawaban, skor, penalti hint, dan status pengerjaan pengguna."
        >
            <Head title="Data Submission" />

            <form
                onSubmit={submit}
                className="nb-card grid gap-4 bg-[#fff1a8] p-5 xl:grid-cols-[minmax(0,1fr)_200px_280px_auto]"
            >
                <input
                    value={search}
                    onChange={(event) => setSearch(event.target.value)}
                    className="nb-input"
                    placeholder="Cari nama atau email pengguna"
                />

                <select
                    value={status}
                    onChange={(event) => setStatus(event.target.value)}
                    className="nb-input"
                >
                    <option value="">Semua status</option>
                    <option value="incorrect">Belum tepat</option>
                    <option value="partially_correct">Sebagian benar</option>
                    <option value="completed">Selesai</option>
                </select>

                <select
                    value={challenge}
                    onChange={(event) => setChallenge(event.target.value)}
                    className="nb-input"
                >
                    <option value="">Semua tantangan</option>

                    {challenges.map((item) => (
                        <option key={item.id} value={item.id}>
                            {item.title}
                        </option>
                    ))}
                </select>

                <button className="nb-button bg-[#ffd93d]">Filter</button>
            </form>

            <div className="mt-7 overflow-x-auto border-[3px] border-black bg-white shadow-[6px_6px_0_#111]">
                <table className="nb-table min-w-[1200px]">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Tantangan</th>
                            <th>Status</th>
                            <th>Baris</th>
                            <th>Kode</th>
                            <th>Penjelasan</th>
                            <th>Penalti</th>
                            <th>Skor Akhir</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        {submissions.data.map((submission) => (
                            <tr key={submission.id}>
                                <td>
                                    <strong>{submission.user.name}</strong>

                                    <p className="mt-1 text-xs font-bold text-neutral-600">
                                        {submission.user.email}
                                    </p>
                                </td>

                                <td className="max-w-xs font-bold">
                                    {submission.challenge.title}
                                </td>

                                <td>
                                    <StatusBadge status={submission.status} />
                                </td>

                                <td className="font-black">
                                    {submission.line_score}
                                </td>

                                <td className="font-black">
                                    {submission.code_score}
                                </td>

                                <td className="font-black">
                                    {submission.explanation_score}
                                </td>

                                <td className="font-black">
                                    {submission.hint_penalty}%
                                </td>

                                <td className="text-lg font-black">
                                    {submission.final_score}
                                </td>

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
                                            "admin.submissions.show",
                                            submission.id,
                                        )}
                                        className="nb-button bg-[#9ed8ff] text-xs"
                                    >
                                        Detail
                                    </Link>
                                </td>
                            </tr>
                        ))}

                        {submissions.data.length === 0 && (
                            <tr>
                                <td
                                    colSpan={10}
                                    className="text-center font-black"
                                >
                                    Submission tidak ditemukan.
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>

            <Pagination links={submissions.links} />
        </AdminLayout>
    );
}
