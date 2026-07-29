import InputError from "@/Components/GalatInput";
import {
    ChallengeFormData,
    ChallengeFormErrors,
    SetChallengeFormData,
} from "@/Components/AdminChallengeForm/tipe";
import { Category, Difficulty } from "@/types";

type BasicInformationSectionProps = {
    data: ChallengeFormData;
    errors: ChallengeFormErrors;
    categories: Category[];
    difficulties: Difficulty[];
    setData: SetChallengeFormData;
    changeDifficulty: (difficultyId: number) => void;
};

export default function BasicInformationSection({
    data,
    errors,
    categories,
    difficulties,
    setData,
    changeDifficulty,
}: BasicInformationSectionProps) {
    return (
        <section className="nb-card bg-white p-6">
            <h2 className="text-2xl font-black">Informasi Dasar</h2>

            <div className="mt-6 grid gap-5 md:grid-cols-2">
                <div>
                    <label className="nb-label">Kategori</label>

                    <select
                        value={data.category_id}
                        onChange={(event) =>
                            setData("category_id", Number(event.target.value))
                        }
                        className="nb-input"
                    >
                        {categories.map((category) => (
                            <option key={category.id} value={category.id}>
                                {category.name}
                            </option>
                        ))}
                    </select>

                    <InputError message={errors.category_id} className="mt-3" />
                </div>

                <div>
                    <label className="nb-label">Tingkat Kesulitan</label>

                    <select
                        value={data.difficulty_id}
                        onChange={(event) =>
                            changeDifficulty(Number(event.target.value))
                        }
                        className="nb-input"
                    >
                        {difficulties.map((difficulty) => (
                            <option key={difficulty.id} value={difficulty.id}>
                                {difficulty.name} — {difficulty.base_points}{" "}
                                poin
                            </option>
                        ))}
                    </select>
                </div>

                <div>
                    <label className="nb-label">Judul Tantangan</label>

                    <input
                        value={data.title}
                        onChange={(event) =>
                            setData("title", event.target.value)
                        }
                        className="nb-input"
                    />

                    <InputError message={errors.title} className="mt-3" />
                </div>

                <div>
                    <label className="nb-label">Slug</label>

                    <input
                        value={data.slug}
                        onChange={(event) =>
                            setData("slug", event.target.value)
                        }
                        className="nb-input"
                        placeholder="Kosongkan untuk dibuat otomatis"
                    />

                    <InputError message={errors.slug} className="mt-3" />
                </div>

                <div>
                    <label className="nb-label">Poin</label>

                    <input
                        type="number"
                        min={10}
                        max={1000}
                        value={data.base_points}
                        onChange={(event) =>
                            setData("base_points", Number(event.target.value))
                        }
                        className="nb-input"
                    />

                    <InputError message={errors.base_points} className="mt-3" />
                </div>

                <div>
                    <label className="nb-label">Status</label>

                    <select
                        value={data.status}
                        onChange={(event) =>
                            setData(
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
                        value={data.description}
                        onChange={(event) =>
                            setData("description", event.target.value)
                        }
                        className="nb-input min-h-32 resize-y"
                    />

                    <InputError message={errors.description} className="mt-3" />
                </div>
            </div>
        </section>
    );
}
