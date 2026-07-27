import CodeEditor from "@/Components/CodeEditor";
import InputError from "@/Components/InputError";
import { AdminChallenge, Category, Difficulty } from "@/types";
import { Link, useForm } from "@inertiajs/react";
import { FormEvent } from "react";

type HintForm = {
    content: string;
    point_penalty: number;
};

type SolutionForm = {
    solution_code: string;
    solution_type: "primary" | "alternative";
    required_keywords: string[];
};

type ChallengeFormData = {
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

type EditableChallenge = AdminChallenge & {
    category_id?: number;
    difficulty_id?: number;
    hints?: HintForm[];
    solutions?: SolutionForm[];
};

type AdminChallengeFormProps = {
    categories: Category[];
    difficulties: Difficulty[];
    challenge?: EditableChallenge;
};

export default function AdminChallengeForm({
    categories,
    difficulties,
    challenge,
}: AdminChallengeFormProps) {
    const editing = Boolean(challenge);

    const form = useForm<ChallengeFormData>({
        category_id:
            challenge?.category_id ??
            challenge?.category.id ??
            categories[0]?.id ??
            0,
        difficulty_id:
            challenge?.difficulty_id ??
            challenge?.difficulty.id ??
            difficulties[0]?.id ??
            0,
        title: challenge?.title ?? "",
        slug: challenge?.slug ?? "",
        description: challenge?.description ?? "",
        broken_code: challenge?.broken_code ?? "",
        buggy_line: challenge?.buggy_line ?? 1,
        explanation: challenge?.explanation ?? "",
        base_points:
            challenge?.base_points ?? difficulties[0]?.base_points ?? 50,
        status: challenge?.status ?? "draft",
        hints: challenge?.hints?.length
            ? challenge.hints.map((hint) => ({
                  content: hint.content,
                  point_penalty: Number(hint.point_penalty),
              }))
            : [
                  {
                      content: "",
                      point_penalty: 10,
                  },
                  {
                      content: "",
                      point_penalty: 20,
                  },
              ],
        solutions: challenge?.solutions?.length
            ? challenge.solutions.map((solution) => ({
                  solution_code: solution.solution_code,
                  solution_type: solution.solution_type,
                  required_keywords: solution.required_keywords ?? [],
              }))
            : [
                  {
                      solution_code: "",
                      solution_type: "primary",
                      required_keywords: [],
                  },
              ],
    });

    const formErrors = form.errors as Record<string, string>;

    const selectedCategory =
        categories.find((category) => category.id === form.data.category_id)
            ?.slug ?? "javascript";

    const changeDifficulty = (difficultyId: number) => {
        const difficulty = difficulties.find(
            (item) => item.id === difficultyId,
        );

        form.setData((current) => ({
            ...current,
            difficulty_id: difficultyId,
            base_points: difficulty?.base_points ?? current.base_points,
        }));
    };

    const updateHint = (
        index: number,
        key: keyof HintForm,
        value: string | number,
    ) => {
        form.setData(
            "hints",
            form.data.hints.map((hint, hintIndex) =>
                hintIndex === index
                    ? {
                          ...hint,
                          [key]: value,
                      }
                    : hint,
            ),
        );
    };

    const addHint = () => {
        if (form.data.hints.length >= 5) {
            return;
        }

        form.setData("hints", [
            ...form.data.hints,
            {
                content: "",
                point_penalty: 10,
            },
        ]);
    };

    const removeHint = (index: number) => {
        if (form.data.hints.length <= 1) {
            return;
        }

        form.setData(
            "hints",
            form.data.hints.filter((_, hintIndex) => hintIndex !== index),
        );
    };

    const updateSolution = (index: number, value: Partial<SolutionForm>) => {
        form.setData(
            "solutions",
            form.data.solutions.map((solution, solutionIndex) =>
                solutionIndex === index
                    ? {
                          ...solution,
                          ...value,
                      }
                    : solution,
            ),
        );
    };

    const addSolution = () => {
        if (form.data.solutions.length >= 10) {
            return;
        }

        form.setData("solutions", [
            ...form.data.solutions,
            {
                solution_code: "",
                solution_type: "alternative",
                required_keywords: [],
            },
        ]);
    };

    const removeSolution = (index: number) => {
        if (form.data.solutions.length <= 1) {
            return;
        }

        form.setData(
            "solutions",
            form.data.solutions.filter(
                (_, solutionIndex) => solutionIndex !== index,
            ),
        );
    };

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        if (editing && challenge) {
            form.put(route("admin.challenges.update", challenge.slug), {
                preserveScroll: true,
            });

            return;
        }

        form.post(route("admin.challenges.store"));
    };

    return (
        <form onSubmit={submit} className="space-y-8">
            <section className="nb-card bg-white p-6">
                <h2 className="text-2xl font-black">Informasi Dasar</h2>

                <div className="mt-6 grid gap-5 md:grid-cols-2">
                    <div>
                        <label className="nb-label">Kategori</label>

                        <select
                            value={form.data.category_id}
                            onChange={(event) =>
                                form.setData(
                                    "category_id",
                                    Number(event.target.value),
                                )
                            }
                            className="nb-input"
                        >
                            {categories.map((category) => (
                                <option key={category.id} value={category.id}>
                                    {category.name}
                                </option>
                            ))}
                        </select>

                        <InputError
                            message={form.errors.category_id}
                            className="mt-3"
                        />
                    </div>

                    <div>
                        <label className="nb-label">Tingkat Kesulitan</label>

                        <select
                            value={form.data.difficulty_id}
                            onChange={(event) =>
                                changeDifficulty(Number(event.target.value))
                            }
                            className="nb-input"
                        >
                            {difficulties.map((difficulty) => (
                                <option
                                    key={difficulty.id}
                                    value={difficulty.id}
                                >
                                    {difficulty.name} — {difficulty.base_points}{" "}
                                    poin
                                </option>
                            ))}
                        </select>
                    </div>

                    <div>
                        <label className="nb-label">Judul Tantangan</label>

                        <input
                            value={form.data.title}
                            onChange={(event) =>
                                form.setData("title", event.target.value)
                            }
                            className="nb-input"
                        />

                        <InputError
                            message={form.errors.title}
                            className="mt-3"
                        />
                    </div>

                    <div>
                        <label className="nb-label">Slug</label>

                        <input
                            value={form.data.slug}
                            onChange={(event) =>
                                form.setData("slug", event.target.value)
                            }
                            className="nb-input"
                            placeholder="Kosongkan untuk dibuat otomatis"
                        />

                        <InputError
                            message={form.errors.slug}
                            className="mt-3"
                        />
                    </div>

                    <div>
                        <label className="nb-label">Poin</label>

                        <input
                            type="number"
                            min={10}
                            max={1000}
                            value={form.data.base_points}
                            onChange={(event) =>
                                form.setData(
                                    "base_points",
                                    Number(event.target.value),
                                )
                            }
                            className="nb-input"
                        />

                        <InputError
                            message={form.errors.base_points}
                            className="mt-3"
                        />
                    </div>

                    <div>
                        <label className="nb-label">Status</label>

                        <select
                            value={form.data.status}
                            onChange={(event) =>
                                form.setData(
                                    "status",
                                    event.target
                                        .value as ChallengeFormData["status"],
                                )
                            }
                            className="nb-input"
                        >
                            <option value="draft">Draft</option>

                            <option value="published">Terbit</option>

                            <option value="inactive">Nonaktif</option>
                        </select>
                    </div>

                    <div className="md:col-span-2">
                        <label className="nb-label">Deskripsi Masalah</label>

                        <textarea
                            value={form.data.description}
                            onChange={(event) =>
                                form.setData("description", event.target.value)
                            }
                            className="nb-input min-h-32 resize-y"
                        />

                        <InputError
                            message={form.errors.description}
                            className="mt-3"
                        />
                    </div>
                </div>
            </section>

            <section className="nb-card bg-[#9ed8ff] p-6">
                <div className="grid gap-5 md:grid-cols-[minmax(0,1fr)_180px]">
                    <div>
                        <h2 className="text-2xl font-black">Kode Bermasalah</h2>

                        <p className="mt-2 font-semibold">
                            Masukkan kode yang sengaja memiliki bug.
                        </p>
                    </div>

                    <div>
                        <label className="nb-label">Baris Bug</label>

                        <input
                            type="number"
                            min={1}
                            value={form.data.buggy_line}
                            onChange={(event) =>
                                form.setData(
                                    "buggy_line",
                                    Number(event.target.value),
                                )
                            }
                            className="nb-input"
                        />

                        <InputError
                            message={form.errors.buggy_line}
                            className="mt-3"
                        />
                    </div>
                </div>

                <div className="mt-6">
                    <CodeEditor
                        value={form.data.broken_code}
                        onChange={(value) => form.setData("broken_code", value)}
                        language={selectedCategory}
                        minHeight="420px"
                    />

                    <InputError
                        message={form.errors.broken_code}
                        className="mt-4"
                    />
                </div>
            </section>

            <section className="nb-card bg-[#ffd93d] p-6">
                <h2 className="text-2xl font-black">Pembahasan Lengkap</h2>

                <textarea
                    value={form.data.explanation}
                    onChange={(event) =>
                        form.setData("explanation", event.target.value)
                    }
                    className="nb-input mt-6 min-h-48 resize-y"
                    placeholder="Jelaskan penyebab bug dan alasan solusi bekerja."
                />

                <InputError
                    message={form.errors.explanation}
                    className="mt-3"
                />
            </section>

            <section className="nb-card bg-[#fff1a8] p-6">
                <div className="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 className="text-2xl font-black">Hint</h2>

                        <p className="mt-2 font-semibold">
                            Hint akan dibuka secara berurutan.
                        </p>
                    </div>

                    <button
                        type="button"
                        onClick={addHint}
                        disabled={form.data.hints.length >= 5}
                        className="nb-button bg-[#9ef0b8] text-sm"
                    >
                        Tambah Hint
                    </button>
                </div>

                <div className="mt-6 grid gap-5">
                    {form.data.hints.map((hint, index) => (
                        <article
                            key={index}
                            className="border-[3px] border-black bg-white p-5 shadow-[4px_4px_0_#111]"
                        >
                            <div className="flex flex-wrap items-center justify-between gap-4">
                                <h3 className="text-lg font-black">
                                    Hint {index + 1}
                                </h3>

                                <button
                                    type="button"
                                    onClick={() => removeHint(index)}
                                    disabled={form.data.hints.length <= 1}
                                    className="nb-button bg-[#ff9c9c] text-xs"
                                >
                                    Hapus
                                </button>
                            </div>

                            <div className="mt-5 grid gap-5 md:grid-cols-[minmax(0,1fr)_180px]">
                                <div>
                                    <label className="nb-label">Isi Hint</label>

                                    <textarea
                                        value={hint.content}
                                        onChange={(event) =>
                                            updateHint(
                                                index,
                                                "content",
                                                event.target.value,
                                            )
                                        }
                                        className="nb-input min-h-28 resize-y"
                                    />

                                    <InputError
                                        message={
                                            formErrors[`hints.${index}.content`]
                                        }
                                        className="mt-3"
                                    />
                                </div>

                                <div>
                                    <label className="nb-label">
                                        Penalti %
                                    </label>

                                    <input
                                        type="number"
                                        min={0}
                                        max={100}
                                        value={hint.point_penalty}
                                        onChange={(event) =>
                                            updateHint(
                                                index,
                                                "point_penalty",
                                                Number(event.target.value),
                                            )
                                        }
                                        className="nb-input"
                                    />
                                </div>
                            </div>
                        </article>
                    ))}
                </div>
            </section>

            <section className="nb-card bg-[#b7a4ff] p-6">
                <div className="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 className="text-2xl font-black">Solusi</h2>

                        <p className="mt-2 font-semibold">
                            Wajib memiliki tepat satu solusi utama.
                        </p>
                    </div>

                    <button
                        type="button"
                        onClick={addSolution}
                        disabled={form.data.solutions.length >= 10}
                        className="nb-button bg-[#9ef0b8] text-sm"
                    >
                        Tambah Alternatif
                    </button>
                </div>

                <InputError message={formErrors.solutions} className="mt-5" />

                <div className="mt-6 grid gap-6">
                    {form.data.solutions.map((solution, index) => (
                        <article
                            key={index}
                            className="border-[3px] border-black bg-white p-5 shadow-[4px_4px_0_#111]"
                        >
                            <div className="flex flex-wrap items-center justify-between gap-4">
                                <select
                                    value={solution.solution_type}
                                    onChange={(event) =>
                                        updateSolution(index, {
                                            solution_type: event.target
                                                .value as SolutionForm["solution_type"],
                                        })
                                    }
                                    className="nb-input max-w-xs"
                                >
                                    <option value="primary">
                                        Solusi Utama
                                    </option>

                                    <option value="alternative">
                                        Solusi Alternatif
                                    </option>
                                </select>

                                <button
                                    type="button"
                                    onClick={() => removeSolution(index)}
                                    disabled={form.data.solutions.length <= 1}
                                    className="nb-button bg-[#ff9c9c] text-xs"
                                >
                                    Hapus
                                </button>
                            </div>

                            <div className="mt-5">
                                <CodeEditor
                                    value={solution.solution_code}
                                    onChange={(value) =>
                                        updateSolution(index, {
                                            solution_code: value,
                                        })
                                    }
                                    language={selectedCategory}
                                    minHeight="350px"
                                />

                                <InputError
                                    message={
                                        formErrors[
                                            `solutions.${index}.solution_code`
                                        ]
                                    }
                                    className="mt-3"
                                />
                            </div>

                            <div className="mt-5">
                                <label className="nb-label">
                                    Kata Kunci Penjelasan
                                </label>

                                <input
                                    value={solution.required_keywords.join(
                                        ", ",
                                    )}
                                    onChange={(event) =>
                                        updateSolution(index, {
                                            required_keywords:
                                                event.target.value
                                                    .split(",")
                                                    .map((keyword) =>
                                                        keyword.trim(),
                                                    )
                                                    .filter(Boolean),
                                        })
                                    }
                                    className="nb-input"
                                    placeholder="array, indeks, length, di luar batas"
                                />

                                <p className="mt-2 text-sm font-bold">
                                    Pisahkan setiap kata kunci dengan koma.
                                </p>
                            </div>
                        </article>
                    ))}
                </div>
            </section>

            <div className="flex flex-wrap justify-end gap-4">
                <Link
                    href={route("admin.challenges.index")}
                    className="nb-button bg-white"
                >
                    Batal
                </Link>

                <button
                    disabled={form.processing}
                    className="nb-button bg-[#9ef0b8] px-8"
                >
                    {form.processing
                        ? "Menyimpan..."
                        : editing
                          ? "Simpan Perubahan"
                          : "Buat Tantangan"}
                </button>
            </div>
        </form>
    );
}
