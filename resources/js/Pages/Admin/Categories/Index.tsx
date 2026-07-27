import InputError from "@/Components/InputError";
import AdminLayout from "@/Layouts/AdminLayout";
import { Category } from "@/types";
import { Head, router, useForm } from "@inertiajs/react";
import { FormEvent, useState } from "react";

type CategoryRow = Category & {
    challenges_count: number;
    is_active: boolean;
};

type CategoryForm = {
    name: string;
    slug: string;
    description: string;
    is_active: boolean;
};

export default function CategoryIndex({
    categories,
}: {
    categories: CategoryRow[];
}) {
    const [editingId, setEditingId] = useState<number | null>(null);

    const createForm = useForm<CategoryForm>({
        name: "",
        slug: "",
        description: "",
        is_active: true,
    });

    const editForm = useForm<CategoryForm>({
        name: "",
        slug: "",
        description: "",
        is_active: true,
    });

    const submitCreate = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        createForm.post(route("admin.categories.store"), {
            preserveScroll: true,
            onSuccess: () => createForm.reset(),
        });
    };

    const startEditing = (category: CategoryRow) => {
        setEditingId(category.id);

        editForm.setData({
            name: category.name,
            slug: category.slug,
            description: category.description ?? "",
            is_active: category.is_active,
        });

        editForm.clearErrors();
    };

    const submitEdit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        if (!editingId) {
            return;
        }

        editForm.put(route("admin.categories.update", editingId), {
            preserveScroll: true,
            onSuccess: () => setEditingId(null),
        });
    };

    const remove = (category: CategoryRow) => {
        if (!window.confirm(`Hapus kategori "${category.name}"?`)) {
            return;
        }

        router.delete(route("admin.categories.destroy", category.id), {
            preserveScroll: true,
        });
    };

    return (
        <AdminLayout
            title="Kelola Kategori"
            description="Kelola bahasa pemrograman yang tersedia pada tantangan."
        >
            <Head title="Kelola Kategori" />

            <section className="nb-card bg-[#ffd93d] p-6">
                <h2 className="text-2xl font-black">Tambah Kategori</h2>

                <form
                    onSubmit={submitCreate}
                    className="mt-6 grid gap-5 lg:grid-cols-2"
                >
                    <div>
                        <label className="nb-label">Nama</label>

                        <input
                            value={createForm.data.name}
                            onChange={(event) =>
                                createForm.setData("name", event.target.value)
                            }
                            className="nb-input"
                            placeholder="JavaScript"
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
                            placeholder="javascript"
                        />

                        <InputError
                            message={createForm.errors.slug}
                            className="mt-3"
                        />
                    </div>

                    <div className="lg:col-span-2">
                        <label className="nb-label">Deskripsi</label>

                        <textarea
                            value={createForm.data.description}
                            onChange={(event) =>
                                createForm.setData(
                                    "description",
                                    event.target.value,
                                )
                            }
                            className="nb-input min-h-28 resize-y"
                        />

                        <InputError
                            message={createForm.errors.description}
                            className="mt-3"
                        />
                    </div>

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
                            className="h-6 w-6 border-[3px] border-black"
                        />
                        Kategori aktif
                    </label>

                    <div className="flex justify-end">
                        <button
                            disabled={createForm.processing}
                            className="nb-button bg-[#9ef0b8]"
                        >
                            Tambah Kategori
                        </button>
                    </div>
                </form>
            </section>

            <section className="mt-8 grid gap-5">
                {categories.map((category) => (
                    <article key={category.id} className="nb-card bg-white p-5">
                        {editingId === category.id ? (
                            <form
                                onSubmit={submitEdit}
                                className="grid gap-5 lg:grid-cols-2"
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

                                    <InputError
                                        message={editForm.errors.name}
                                        className="mt-3"
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

                                    <InputError
                                        message={editForm.errors.slug}
                                        className="mt-3"
                                    />
                                </div>

                                <div className="lg:col-span-2">
                                    <label className="nb-label">
                                        Deskripsi
                                    </label>

                                    <textarea
                                        value={editForm.data.description}
                                        onChange={(event) =>
                                            editForm.setData(
                                                "description",
                                                event.target.value,
                                            )
                                        }
                                        className="nb-input min-h-28"
                                    />
                                </div>

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
                                    Kategori aktif
                                </label>

                                <div className="flex flex-wrap justify-end gap-3">
                                    <button
                                        type="button"
                                        onClick={() => setEditingId(null)}
                                        className="nb-button bg-white"
                                    >
                                        Batal
                                    </button>

                                    <button
                                        disabled={editForm.processing}
                                        className="nb-button bg-[#9ef0b8]"
                                    >
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        ) : (
                            <div className="flex flex-col justify-between gap-5 md:flex-row md:items-center">
                                <div>
                                    <div className="flex flex-wrap items-center gap-3">
                                        <h2 className="text-xl font-black">
                                            {category.name}
                                        </h2>

                                        <span
                                            className={`nb-badge ${
                                                category.is_active
                                                    ? "bg-[#9ef0b8]"
                                                    : "bg-[#ff9c9c]"
                                            }`}
                                        >
                                            {category.is_active
                                                ? "Aktif"
                                                : "Nonaktif"}
                                        </span>
                                    </div>

                                    <p className="mt-2 font-mono text-sm font-bold">
                                        /{category.slug}
                                    </p>

                                    <p className="mt-3 max-w-3xl font-semibold text-neutral-700">
                                        {category.description ||
                                            "Tidak ada deskripsi."}
                                    </p>

                                    <p className="mt-3 text-sm font-black">
                                        {category.challenges_count} tantangan
                                    </p>
                                </div>

                                <div className="flex flex-wrap gap-3">
                                    <button
                                        type="button"
                                        onClick={() => startEditing(category)}
                                        className="nb-button bg-[#9ed8ff] text-sm"
                                    >
                                        Edit
                                    </button>

                                    <button
                                        type="button"
                                        onClick={() => remove(category)}
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
