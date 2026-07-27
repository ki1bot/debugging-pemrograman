import Pagination from "@/Components/Pagination";
import StatusBadge from "@/Components/StatusBadge";
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
        category: string | number;
        difficulty: string | number;
        status: string;
    };
};

export default function ChallengeIndex({
    challenges,
    categories,
    difficulties,
    filters,
}: ChallengeIndexProps) {
    const [search, setSearch] = useState(filters.search);

    const [category, setCategory] = useState(String(filters.category ?? ""));

    const [difficulty, setDifficulty] = useState(
        String(filters.difficulty ?? ""),
    );

    const [status, setStatus] = useState(filters.status);

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        router.get(
            route("admin.challenges.index"),
            {
                search: search || undefined,
                category: category || undefined,
                difficulty: difficulty || undefined,
                status: status || undefined,
            },
            {
                preserveState: true,
                replace: true,
            },
        );
    };

    const remove = (challenge: AdminChallenge) => {
        if (!window.confirm(`Nonaktifkan dan arsipkan "${challenge.title}"?`)) {
            return;
        }

        router.delete(route("admin.challenges.destroy", challenge.slug));
    };

    return (
        <AdminLayout
            title="Kelola Tantangan"
            description="Tambah, ubah, terbitkan, nonaktifkan, dan tinjau statistik setiap tantangan."
        >
            <Head title="Kelola Tantangan" />

            <div className="flex justify-end">
                <Link
                    href={route("admin.challenges.create")}
                    className="nb-button bg-[#9ef0b8]"
                >
                    Tambah Tantangan
                </Link>
            </div>

            <form
                onSubmit={submit}
                className="nb-card mt-6 grid gap-4 bg-[#fff1a8] p-5 xl:grid-cols-[minmax(0,1fr)_180px_180px_180px_auto]"
            >
                <input
                    value={search}
                    onChange={(event) => setSearch(event.target.value)}
                    className="nb-input"
                    placeholder="Cari judul tantangan"
                />

                <select
                    value={category}
                    onChange={(event) => setCategory(event.target.value)}
                    className="nb-input"
                >
                    <option value="">Semua kategori</option>

                    {categories.map((item) => (
                        <option key={item.id} value={item.id}>
                            {item.name}
                        </option>
                    ))}
                </select>

                <select
                    value={difficulty}
                    onChange={(event) => setDifficulty(event.target.value)}
                    className="nb-input"
                >
                    <option value="">Semua kesulitan</option>

                    {difficulties.map((item) => (
                        <option key={item.id} value={item.id}>
                            {item.name}
                        </option>
                    ))}
                </select>

                <select
                    value={status}
                    onChange={(event) => setStatus(event.target.value)}
                    className="nb-input"
                >
                    <option value="">Semua status</option>
                    <option value="draft">Draft</option>
                    <option value="published">Terbit</option>
                    <option value="inactive">Nonaktif</option>
                </select>

                <button className="nb-button bg-[#ffd93d]">Filter</button>
            </form>

            <div className="mt-7 overflow-x-auto border-[3px] border-black bg-white shadow-[6px_6px_0_#111]">
                <table className="nb-table min-w-[1150px]">
                    <thead>
                        <tr>
                            <th>Tantangan</th>
                            <th>Kategori</th>
                            <th>Kesulitan</th>
                            <th>Status</th>
                            <th>Poin</th>
                            <th>Konten</th>
                            <th>Submission</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        {challenges.data.map((challenge) => (
                            <tr key={challenge.id}>
                                <td>
                                    <strong>{challenge.title}</strong>

                                    <p className="mt-1 max-w-sm text-xs font-bold text-neutral-600">
                                        {challenge.slug}
                                    </p>
                                </td>

                                <td>
                                    <span className="nb-badge bg-[#9ed8ff]">
                                        {challenge.category.name}
                                    </span>
                                </td>

                                <td className="font-bold">
                                    {challenge.difficulty.name}
                                </td>

                                <td>
                                    <StatusBadge status={challenge.status} />
                                </td>

                                <td className="font-black">
                                    {challenge.base_points}
                                </td>

                                <td className="text-sm font-bold">
                                    {challenge.hints_count} hint
                                    <br />
                                    {challenge.solutions_count} solusi
                                </td>

                                <td className="font-black">
                                    {challenge.submissions_count}
                                </td>

                                <td>
                                    <div className="flex gap-3">
                                        <Link
                                            href={route(
                                                "admin.challenges.edit",
                                                challenge.slug,
                                            )}
                                            className="nb-button bg-[#ffd93d] text-xs"
                                        >
                                            Edit
                                        </Link>

                                        <button
                                            type="button"
                                            onClick={() => remove(challenge)}
                                            className="nb-button bg-[#ff9c9c] text-xs"
                                        >
                                            Arsipkan
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        ))}

                        {challenges.data.length === 0 && (
                            <tr>
                                <td
                                    colSpan={8}
                                    className="text-center font-black"
                                >
                                    Tantangan tidak ditemukan.
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>

            <Pagination links={challenges.links} />
        </AdminLayout>
    );
}
