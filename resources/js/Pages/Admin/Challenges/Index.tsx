import EmptyState from "@/Components/EmptyState";
import Pagination from "@/Components/Pagination";
import AdminLayout from "@/Layouts/AdminLayout";
import { AdminChallenge, Category, Difficulty, Paginator } from "@/types";
import { Head, Link, router } from "@inertiajs/react";
import { FormEvent, useState } from "react";

type ChallengeIndexProps = {
    challenges: Paginator<AdminChallenge>;
    categories: Category[];
    difficulties: Difficulty[];
    filters: {
        search: string;
        category: number | string;
        difficulty: number | string;
        status: string;
    };
};

const statusLabels: Record<AdminChallenge["status"], string> = {
    draft: "Draft",
    published: "Dipublikasikan",
    inactive: "Nonaktif",
};

const statusClasses: Record<AdminChallenge["status"], string> = {
    draft: "bg-[#fff1a8]",
    published: "bg-[#9ef0b8]",
    inactive: "bg-[#ff9c9c]",
};

export default function ChallengeIndex({
    challenges,
    categories,
    difficulties,
    filters,
}: ChallengeIndexProps) {
    const [search, setSearch] = useState(filters.search ?? "");
    const [category, setCategory] = useState(
        filters.category === "" ? "" : String(filters.category),
    );
    const [difficulty, setDifficulty] = useState(
        filters.difficulty === "" ? "" : String(filters.difficulty),
    );
    const [status, setStatus] = useState(filters.status ?? "");

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        router.get(
            route("admin.challenges.index"),
            {
                search: search.trim() || undefined,
                category: category || undefined,
                difficulty: difficulty || undefined,
                status: status || undefined,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    };

    const reset = () => {
        setSearch("");
        setCategory("");
        setDifficulty("");
        setStatus("");

        router.get(
            route("admin.challenges.index"),
            {},
            {
                preserveState: false,
                preserveScroll: true,
                replace: true,
            },
        );
    };

    const remove = (challenge: AdminChallenge) => {
        if (
            !window.confirm(
                `Nonaktifkan dan arsipkan tantangan "${challenge.title}"?`,
            )
        ) {
            return;
        }

        router.delete(route("admin.challenges.destroy", challenge.slug), {
            preserveScroll: true,
        });
    };

    return (
        <AdminLayout
            title="Kelola Tantangan"
            description="Kelola seluruh tantangan debugging, status publikasi, kategori, tingkat kesulitan, solusi, dan hint."
        >
            <Head title="Kelola Tantangan" />

            <section className="nb-card bg-[#ffd93d] p-5 sm:p-6">
                <div className="flex flex-col justify-between gap-5 lg:flex-row lg:items-center">
                    <div>
                        <h2 className="text-2xl font-black">
                            Daftar Tantangan
                        </h2>

                        <p className="mt-2 max-w-3xl font-semibold leading-7 text-neutral-700">
                            Cari, filter, tambah, edit, atau nonaktifkan
                            tantangan yang tersedia di platform.
                        </p>
                    </div>

                    <Link
                        href={route("admin.challenges.create")}
                        className="nb-button bg-[#9ef0b8] text-center"
                    >
                        Tambah Tantangan
                    </Link>
                </div>

                <form
                    onSubmit={submit}
                    className="mt-6 grid gap-5 xl:grid-cols-[minmax(0,1fr)_200px_200px_200px_auto]"
                >
                    <div>
                        <label htmlFor="search" className="nb-label">
                            Cari Tantangan
                        </label>

                        <input
                            id="search"
                            type="search"
                            value={search}
                            onChange={(event) => setSearch(event.target.value)}
                            className="nb-input bg-white"
                            placeholder="Judul atau deskripsi"
                        />
                    </div>

                    <div>
                        <label htmlFor="category" className="nb-label">
                            Kategori
                        </label>

                        <select
                            id="category"
                            value={category}
                            onChange={(event) =>
                                setCategory(event.target.value)
                            }
                            className="nb-input bg-white"
                        >
                            <option value="">Semua kategori</option>

                            {categories.map((item) => (
                                <option key={item.id} value={String(item.id)}>
                                    {item.name}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div>
                        <label htmlFor="difficulty" className="nb-label">
                            Kesulitan
                        </label>

                        <select
                            id="difficulty"
                            value={difficulty}
                            onChange={(event) =>
                                setDifficulty(event.target.value)
                            }
                            className="nb-input bg-white"
                        >
                            <option value="">Semua kesulitan</option>

                            {difficulties.map((item) => (
                                <option key={item.id} value={String(item.id)}>
                                    {item.name}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div>
                        <label htmlFor="status" className="nb-label">
                            Status
                        </label>

                        <select
                            id="status"
                            value={status}
                            onChange={(event) => setStatus(event.target.value)}
                            className="nb-input bg-white"
                        >
                            <option value="">Semua status</option>
                            <option value="draft">Draft</option>
                            <option value="published">Dipublikasikan</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                    </div>

                    <div className="flex items-end gap-3">
                        <button
                            type="submit"
                            className="nb-button flex-1 bg-[#9ed8ff]"
                        >
                            Terapkan
                        </button>

                        <button
                            type="button"
                            onClick={reset}
                            className="nb-button bg-white"
                        >
                            Hapus
                        </button>
                    </div>
                </form>
            </section>

            <div className="mt-8 flex flex-wrap items-center justify-between gap-4">
                <p className="font-black">
                    Menampilkan {challenges.from ?? 0}–{challenges.to ?? 0} dari{" "}
                    {challenges.total} tantangan
                </p>

                {(filters.search ||
                    filters.category ||
                    filters.difficulty ||
                    filters.status) && (
                    <span className="nb-badge bg-[#b7a4ff]">
                        Filter sedang digunakan
                    </span>
                )}
            </div>

            {challenges.data.length > 0 ? (
                <>
                    <section className="mt-6 grid gap-5">
                        {challenges.data.map((challenge) => (
                            <article
                                key={challenge.id}
                                className="nb-card bg-white p-5 sm:p-6"
                            >
                                <div className="flex flex-col justify-between gap-6 xl:flex-row xl:items-center">
                                    <div className="min-w-0 flex-1">
                                        <div className="flex flex-wrap items-center gap-2">
                                            <span className="nb-badge bg-[#9ed8ff]">
                                                {challenge.category.name}
                                            </span>

                                            <span className="nb-badge bg-[#ffbd70]">
                                                {challenge.difficulty.name}
                                            </span>

                                            <span
                                                className={`nb-badge ${
                                                    statusClasses[
                                                        challenge.status
                                                    ]
                                                }`}
                                            >
                                                {statusLabels[challenge.status]}
                                            </span>
                                        </div>

                                        <h2 className="mt-4 text-2xl font-black tracking-[-0.03em]">
                                            {challenge.title}
                                        </h2>

                                        <p className="mt-3 max-w-4xl font-semibold leading-7 text-neutral-700">
                                            {challenge.description}
                                        </p>

                                        <div className="mt-5 flex flex-wrap gap-2">
                                            <span className="nb-badge bg-[#fff1a8]">
                                                {challenge.base_points} poin
                                            </span>

                                            <span className="nb-badge bg-white">
                                                {challenge.submissions_count ??
                                                    0}{" "}
                                                jawaban
                                            </span>

                                            <span className="nb-badge bg-white">
                                                {challenge.solutions_count ?? 0}{" "}
                                                solusi
                                            </span>

                                            <span className="nb-badge bg-white">
                                                {challenge.hints_count ?? 0}{" "}
                                                hint
                                            </span>
                                        </div>

                                        <p className="mt-4 text-sm font-bold text-neutral-600">
                                            Dibuat oleh{" "}
                                            {challenge.creator?.name ??
                                                "Tidak diketahui"}
                                        </p>
                                    </div>

                                    <div className="flex shrink-0 flex-wrap gap-3 xl:justify-end">
                                        <Link
                                            href={route(
                                                "admin.challenges.edit",
                                                challenge.slug,
                                            )}
                                            className="nb-button bg-[#9ed8ff] text-sm"
                                        >
                                            Edit
                                        </Link>

                                        <button
                                            type="button"
                                            onClick={() => remove(challenge)}
                                            className="nb-button bg-[#ff9c9c] text-sm"
                                        >
                                            Nonaktifkan
                                        </button>
                                    </div>
                                </div>
                            </article>
                        ))}
                    </section>

                    <Pagination links={challenges.links} />
                </>
            ) : (
                <div className="mt-8">
                    <EmptyState
                        title="Tantangan tidak ditemukan"
                        description="Tidak ada tantangan yang sesuai dengan filter saat ini. Hapus beberapa filter atau buat tantangan baru."
                    />
                </div>
            )}
        </AdminLayout>
    );
}
