import ChallengeCard from "@/Components/KartuTantangan";
import EmptyState from "@/Components/KeadaanKosong";
import Pagination from "@/Components/Paginasi";
import PublicLayout from "@/Layouts/TataLetakPublik";
import {
    Category,
    ChallengeCard as ChallengeCardType,
    Difficulty,
    Paginator,
} from "@/types";
import { Head, router } from "@inertiajs/react";
import { FormEvent, useState } from "react";

type ChallengeIndexProps = {
    challenges: Paginator<ChallengeCardType>;
    categories: Category[];
    difficulties: Difficulty[];
    filters: {
        search: string;
        category: string;
        difficulty: string;
    };
};

export default function ChallengeIndex({
    challenges,
    categories,
    difficulties,
    filters,
}: ChallengeIndexProps) {
    const [search, setSearch] = useState(filters.search);
    const [category, setCategory] = useState(filters.category);
    const [difficulty, setDifficulty] = useState(filters.difficulty);

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        router.get(
            route("challenges.index"),
            {
                search: search || undefined,
                category: category || undefined,
                difficulty: difficulty || undefined,
            },
            {
                preserveState: true,
                replace: true,
            },
        );
    };

    const reset = () => {
        setSearch("");
        setCategory("");
        setDifficulty("");

        router.get(
            route("challenges.index"),
            {},
            {
                preserveState: false,
                replace: true,
            },
        );
    };

    return (
        <PublicLayout>
            <Head title="Daftar Tantangan" />

            <section className="border-b-[3px] border-black bg-[#9ed8ff]">
                <div className="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
                    <p className="text-sm font-black uppercase tracking-[0.18em]">
                        Daftar Latihan
                    </p>

                    <h1 className="page-title mt-4">
                        Pilih bug yang ingin kamu pecahkan.
                    </h1>

                    <p className="mt-6 max-w-3xl text-lg font-semibold leading-8">
                        Gunakan pencarian dan filter untuk menemukan latihan
                        yang sesuai. Kode jawabanmu tidak akan dijalankan di
                        server.
                    </p>
                </div>
            </section>

            <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <form
                    onSubmit={submit}
                    className="nb-card grid gap-5 bg-[#fff1a8] p-5 lg:grid-cols-[minmax(0,1fr)_220px_220px_auto]"
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
                            className="nb-input"
                            placeholder="Contoh: array, JOIN, atau session"
                        />
                    </div>

                    <div>
                        <label htmlFor="category" className="nb-label">
                            Bahasa
                        </label>

                        <select
                            id="category"
                            value={category}
                            onChange={(event) =>
                                setCategory(event.target.value)
                            }
                            className="nb-input"
                        >
                            <option value="">Semua bahasa</option>

                            {categories.map((item) => (
                                <option key={item.id} value={item.slug}>
                                    {item.name}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div>
                        <label htmlFor="difficulty" className="nb-label">
                            Tingkat Kesulitan
                        </label>

                        <select
                            id="difficulty"
                            value={difficulty}
                            onChange={(event) =>
                                setDifficulty(event.target.value)
                            }
                            className="nb-input"
                        >
                            <option value="">Semua tingkat</option>

                            {difficulties.map((item) => (
                                <option key={item.id} value={item.slug}>
                                    {item.name}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div className="flex items-end gap-3">
                        <button
                            type="submit"
                            className="nb-button flex-1 bg-[#ffd93d]"
                        >
                            Cari
                        </button>

                        <button
                            type="button"
                            onClick={reset}
                            className="nb-button bg-white"
                        >
                            Hapus Filter
                        </button>
                    </div>
                </form>

                <div className="mt-8 flex flex-wrap items-center justify-between gap-4">
                    <p className="font-black">
                        Menampilkan {challenges.from ?? 0}–{challenges.to ?? 0}{" "}
                        dari {challenges.total} tantangan
                    </p>

                    {(filters.search ||
                        filters.category ||
                        filters.difficulty) && (
                        <span className="nb-badge bg-[#b7a4ff]">
                            Filter sedang digunakan
                        </span>
                    )}
                </div>

                {challenges.data.length > 0 ? (
                    <>
                        <div className="mt-7 grid gap-7 md:grid-cols-2 xl:grid-cols-3">
                            {challenges.data.map((challenge) => (
                                <ChallengeCard
                                    key={challenge.id}
                                    challenge={challenge}
                                />
                            ))}
                        </div>

                        <Pagination links={challenges.links} />
                    </>
                ) : (
                    <div className="mt-8">
                        <EmptyState
                            title="Tantangan tidak ditemukan"
                            description="Coba gunakan kata pencarian lain atau hapus beberapa filter."
                        />
                    </div>
                )}
            </div>
        </PublicLayout>
    );
}
