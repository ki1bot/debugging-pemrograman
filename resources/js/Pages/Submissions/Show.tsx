import CodeEditor from "@/Components/CodeEditor";
import StatusBadge from "@/Components/StatusBadge";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { SubmissionResult } from "@/types";
import { Head, Link } from "@inertiajs/react";

type SubmissionShowProps = {
    submission: SubmissionResult;
};

export default function SubmissionShow({ submission }: SubmissionShowProps) {
    const maximumScore = submission.challenge.base_points;

    return (
        <AuthenticatedLayout
            header={
                <div className="flex flex-col justify-between gap-5 md:flex-row md:items-end">
                    <div>
                        <StatusBadge status={submission.status} />

                        <h1 className="mt-5 text-3xl font-black tracking-[-0.05em] sm:text-4xl">
                            Hasil: {submission.challenge.title}
                        </h1>
                    </div>

                    <div className="border-[3px] border-black bg-white px-6 py-4 shadow-[4px_4px_0_#111]">
                        <p className="text-xs font-black uppercase tracking-wide">
                            Skor Akhir
                        </p>

                        <p className="mt-1 text-3xl font-black">
                            {submission.final_score}/{maximumScore}
                        </p>
                    </div>
                </div>
            }
        >
            <Head title={`Hasil ${submission.challenge.title}`} />

            <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <section className="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    <article className="nb-card bg-[#ff9c9c] p-5">
                        <p className="text-xs font-black uppercase tracking-[0.14em]">
                            Baris Salah
                        </p>

                        <p className="mt-3 text-4xl font-black">
                            {submission.line_score}
                        </p>

                        <p className="mt-2 text-sm font-bold">
                            Maksimal 30% poin
                        </p>
                    </article>

                    <article className="nb-card bg-[#9ed8ff] p-5">
                        <p className="text-xs font-black uppercase tracking-[0.14em]">
                            Perbaikan Kode
                        </p>

                        <p className="mt-3 text-4xl font-black">
                            {submission.code_score}
                        </p>

                        <p className="mt-2 text-sm font-bold">
                            Maksimal 50% poin
                        </p>
                    </article>

                    <article className="nb-card bg-[#b7a4ff] p-5">
                        <p className="text-xs font-black uppercase tracking-[0.14em]">
                            Penjelasan
                        </p>

                        <p className="mt-3 text-4xl font-black">
                            {submission.explanation_score}
                        </p>

                        <p className="mt-2 text-sm font-bold">
                            Maksimal 20% poin
                        </p>
                    </article>

                    <article className="nb-card bg-[#ffd93d] p-5">
                        <p className="text-xs font-black uppercase tracking-[0.14em]">
                            Penalti Hint
                        </p>

                        <p className="mt-3 text-4xl font-black">
                            {submission.hint_penalty}%
                        </p>

                        <p className="mt-2 text-sm font-bold">
                            Dikurangi dari skor mentah
                        </p>
                    </article>
                </section>

                <section className="mt-10 grid gap-8 xl:grid-cols-2">
                    <article className="nb-card bg-white p-6">
                        <div className="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <span className="nb-badge bg-[#ff9c9c]">
                                    Jawaban Anda
                                </span>

                                <h2 className="mt-4 text-2xl font-black">
                                    Kode yang dikirim
                                </h2>
                            </div>

                            <span className="font-black">
                                Baris dipilih: {submission.selected_line}
                            </span>
                        </div>

                        <div className="mt-6">
                            <CodeEditor
                                value={submission.submitted_code}
                                language={submission.challenge.category.slug}
                                readOnly
                                minHeight="380px"
                            />
                        </div>
                    </article>

                    <article className="nb-card bg-[#9ef0b8] p-6">
                        <span className="nb-badge bg-white">Solusi Utama</span>

                        <h2 className="mt-4 text-2xl font-black">
                            Kode perbaikan yang diterima
                        </h2>

                        <div className="mt-6">
                            <CodeEditor
                                value={
                                    submission.challenge.primary_solution ??
                                    "Solusi belum tersedia."
                                }
                                language={submission.challenge.category.slug}
                                readOnly
                                minHeight="380px"
                            />
                        </div>
                    </article>
                </section>

                <section className="mt-8 grid gap-8 lg:grid-cols-2">
                    <article className="nb-card bg-[#fff1a8] p-6">
                        <span className="nb-badge bg-white">
                            Penjelasan Anda
                        </span>

                        <p className="mt-5 whitespace-pre-wrap font-semibold leading-8 text-neutral-700">
                            {submission.submitted_explanation}
                        </p>
                    </article>

                    <article className="nb-card bg-[#9ed8ff] p-6">
                        <span className="nb-badge bg-white">Pembahasan</span>

                        <p className="mt-5 whitespace-pre-wrap font-semibold leading-8 text-neutral-700">
                            {submission.challenge.explanation}
                        </p>

                        <div className="mt-6 border-[3px] border-black bg-white p-4 shadow-[3px_3px_0_#111]">
                            <p className="text-sm font-black uppercase tracking-wide">
                                Baris bug yang benar
                            </p>

                            <p className="mt-2 text-2xl font-black">
                                Baris {submission.challenge.buggy_line}
                            </p>
                        </div>
                    </article>
                </section>

                {submission.challenge.alternative_solutions.length > 0 && (
                    <section className="nb-card mt-8 bg-[#b7a4ff] p-6">
                        <span className="nb-badge bg-white">
                            Alternatif Solusi
                        </span>

                        <div className="mt-6 grid gap-6">
                            {submission.challenge.alternative_solutions.map(
                                (solution, index) => (
                                    <div key={`${index}-${solution}`}>
                                        <h3 className="mb-3 font-black">
                                            Alternatif {index + 1}
                                        </h3>

                                        <CodeEditor
                                            value={solution}
                                            language={
                                                submission.challenge.category
                                                    .slug
                                            }
                                            readOnly
                                            minHeight="280px"
                                        />
                                    </div>
                                ),
                            )}
                        </div>
                    </section>
                )}

                <section className="mt-10 flex flex-wrap justify-center gap-4">
                    <Link
                        href={route(
                            "challenges.show",
                            submission.challenge.slug,
                        )}
                        className="nb-button bg-[#ffd93d]"
                    >
                        Kerjakan Lagi
                    </Link>

                    <Link
                        href={route("history.index")}
                        className="nb-button bg-[#9ed8ff]"
                    >
                        Lihat Riwayat
                    </Link>

                    <Link
                        href={route("challenges.index")}
                        className="nb-button bg-white"
                    >
                        Tantangan Lain
                    </Link>
                </section>
            </div>
        </AuthenticatedLayout>
    );
}
