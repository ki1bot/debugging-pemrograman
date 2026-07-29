import InputError from "@/Components/GalatInput";
import AdminLayout from "@/Layouts/TataLetakAdmin";
import { Difficulty } from "@/types";
import { Head, router, useForm } from "@inertiajs/react";
import { FormEvent, useState } from "react";

type DifficultyRow = Difficulty & {
    challenges_count: number;
    is_active: boolean;
};

type DifficultyForm = {
    name: string;
    slug: string;
    base_points: number;
    is_active: boolean;
};

export default function DifficultyIndex({
    difficulties,
}: {
    difficulties: DifficultyRow[];
}) {
    const [editingId, setEditingId] = useState<number | null>(null);

    const createForm = useForm<DifficultyForm>({
        name: "",
        slug: "",
        base_points: 50,
        is_active: true,
    });

    const editForm = useForm<DifficultyForm>({
        name: "",
        slug: "",
        base_points: 50,
        is_active: true,
    });

    const submitCreate = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        createForm.post(route("admin.difficulties.store"), {
            preserveScroll: true,
            onSuccess: () => createForm.reset(),
        });
    };

    const startEditing = (difficulty: DifficultyRow) => {
        setEditingId(difficulty.id);

        editForm.setData({
            name: difficulty.name,
            slug: difficulty.slug,
            base_points: difficulty.base_points,
            is_active: difficulty.is_active,
        });

        editForm.clearErrors();
    };

    const submitEdit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        if (!editingId) {
            return;
        }

        editForm.put(route("admin.difficulties.update", editingId), {
            preserveScroll: true,
            onSuccess: () => setEditingId(null),
        });
    };

    const remove = (difficulty: DifficultyRow) => {
        if (!window.confirm(`Hapus tingkat "${difficulty.name}"?`)) {
            return;
        }

        router.delete(route("admin.difficulties.destroy", difficulty.id), {
            preserveScroll: true,
        });
    };

    return (
        <AdminLayout
            title="Kelola Tingkat Kesulitan"
            description="Atur nama tingkat kesulitan dan jumlah poin dasar tantangan."
        >
            <Head title="Kelola Kesulitan" />

            <section className="nb-card bg-[#9ed8ff] p-6">
                <h2 className="text-2xl font-black">
                    Tambah Tingkat Kesulitan
                </h2>

                <form
                    onSubmit={submitCreate}
                    className="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-4"
                >
                    <div>
                        <label className="nb-label">Nama</label>

                        <input
                            value={createForm.data.name}
                            onChange={(event) =>
                                createForm.setData("name", event.target.value)
                            }
                            className="nb-input"
                            placeholder="Mudah"
                        />

                        <InputError
                            message={createForm.errors.name}
                            className="mt-3"
                        />
                    </div>

                    <div>
                        <label className="nb-label">Slug</label>

                        <input
                            value={createForm.data.slug}
                            onChange={(event) =>
                                createForm.setData("slug", event.target.value)
                            }
                            className="nb-input"
                            placeholder="mudah"
                        />

                        <InputError
                            message={createForm.errors.slug}
                            className="mt-3"
                        />
                    </div>

                    <div>
                        <label className="nb-label">Poin Dasar</label>

                        <input
                            type="number"
                            min={10}
                            max={1000}
                            value={createForm.data.base_points}
                            onChange={(event) =>
                                createForm.setData(
                                    "base_points",
                                    Number(event.target.value),
                                )
                            }
                            className="nb-input"
                        />

                        <InputError
                            message={createForm.errors.base_points}
                            className="mt-3"
                        />
                    </div>

                    <div className="flex flex-col justify-end gap-4">
                        <label className="flex items-center gap-3 font-black">
                            <input
                                type="checkbox"
                                checked={createForm.data.is_active}
                                onChange={(event) =>
                                    createForm.setData(
                                        "is_active",
                                        event.target.checked,
                                    )
                                }
                                className="h-6 w-6"
                            />
                            Aktif
                        </label>

                        <button
                            disabled={createForm.processing}
                            className="nb-button bg-[#ffd93d]"
                        >
                            Tambah
                        </button>
                    </div>
                </form>
            </section>

            <section className="mt-8 grid gap-5">
                {difficulties.map((difficulty) => (
                    <article
                        key={difficulty.id}
                        className="nb-card bg-white p-5"
                    >
                        {editingId === difficulty.id ? (
                            <form
                                onSubmit={submitEdit}
                                className="grid gap-5 md:grid-cols-2 xl:grid-cols-4"
                            >
                                <div>
                                    <label className="nb-label">Nama</label>

                                    <input
                                        value={editForm.data.name}
                                        onChange={(event) =>
                                            editForm.setData(
                                                "name",
                                                event.target.value,
                                            )
                                        }
                                        className="nb-input"
                                    />
                                </div>

                                <div>
                                    <label className="nb-label">Slug</label>

                                    <input
                                        value={editForm.data.slug}
                                        onChange={(event) =>
                                            editForm.setData(
                                                "slug",
                                                event.target.value,
                                            )
                                        }
                                        className="nb-input"
                                    />
                                </div>

                                <div>
                                    <label className="nb-label">
                                        Poin Dasar
                                    </label>

                                    <input
                                        type="number"
                                        min={10}
                                        max={1000}
                                        value={editForm.data.base_points}
                                        onChange={(event) =>
                                            editForm.setData(
                                                "base_points",
                                                Number(event.target.value),
                                            )
                                        }
                                        className="nb-input"
                                    />
                                </div>

                                <div className="flex flex-col justify-end gap-3">
                                    <label className="flex items-center gap-3 font-black">
                                        <input
                                            type="checkbox"
                                            checked={editForm.data.is_active}
                                            onChange={(event) =>
                                                editForm.setData(
                                                    "is_active",
                                                    event.target.checked,
                                                )
                                            }
                                            className="h-6 w-6"
                                        />
                                        Aktif
                                    </label>

                                    <div className="flex gap-3">
                                        <button
                                            type="button"
                                            onClick={() => setEditingId(null)}
                                            className="nb-button flex-1 bg-white text-sm"
                                        >
                                            Batal
                                        </button>

                                        <button
                                            disabled={editForm.processing}
                                            className="nb-button flex-1 bg-[#9ef0b8] text-sm"
                                        >
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        ) : (
                            <div className="flex flex-col justify-between gap-5 md:flex-row md:items-center">
                                <div>
                                    <div className="flex flex-wrap items-center gap-3">
                                        <h2 className="text-2xl font-black">
                                            {difficulty.name}
                                        </h2>

                                        <span
                                            className={`nb-badge ${
                                                difficulty.is_active
                                                    ? "bg-[#9ef0b8]"
                                                    : "bg-[#ff9c9c]"
                                            }`}
                                        >
                                            {difficulty.is_active
                                                ? "Aktif"
                                                : "Nonaktif"}
                                        </span>
                                    </div>

                                    <p className="mt-3 font-black">
                                        {difficulty.base_points} poin ·{" "}
                                        {difficulty.challenges_count} tantangan
                                    </p>

                                    <p className="mt-2 font-mono text-sm">
                                        /{difficulty.slug}
                                    </p>
                                </div>

                                <div className="flex gap-3">
                                    <button
                                        type="button"
                                        onClick={() => startEditing(difficulty)}
                                        className="nb-button bg-[#9ed8ff] text-sm"
                                    >
                                        Edit
                                    </button>

                                    <button
                                        type="button"
                                        onClick={() => remove(difficulty)}
                                        className="nb-button bg-[#ff9c9c] text-sm"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        )}
                    </article>
                ))}
            </section>
        </AdminLayout>
    );
}
