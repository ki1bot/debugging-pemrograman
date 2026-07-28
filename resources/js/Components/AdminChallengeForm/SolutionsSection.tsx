import CodeEditor from "@/Components/CodeEditor";
import InputError from "@/Components/InputError";
import {
    ChallengeFormErrors,
    SolutionForm,
} from "@/Components/AdminChallengeForm/types";

type SolutionsSectionProps = {
    solutions: SolutionForm[];
    errors: ChallengeFormErrors;
    selectedCategory: string;
    addSolution: () => void;
    removeSolution: (index: number) => void;
    updateSolution: (index: number, value: Partial<SolutionForm>) => void;
};

export default function SolutionsSection({
    solutions,
    errors,
    selectedCategory,
    addSolution,
    removeSolution,
    updateSolution,
}: SolutionsSectionProps) {
    return (
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
                    disabled={solutions.length >= 10}
                    className="nb-button bg-[#9ef0b8] text-sm"
                >
                    Tambah Alternatif
                </button>
            </div>

            <InputError message={errors.solutions} className="mt-5" />

            <div className="mt-6 grid gap-6">
                {solutions.map((solution, index) => (
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
                                <option value="primary">Solusi Utama</option>

                                <option value="alternative">
                                    Solusi Alternatif
                                </option>
                            </select>

                            <button
                                type="button"
                                onClick={() => removeSolution(index)}
                                disabled={solutions.length <= 1}
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
                                    errors[`solutions.${index}.solution_code`]
                                }
                                className="mt-3"
                            />
                        </div>

                        <div className="mt-5">
                            <label className="nb-label">
                                Kata Kunci Penjelasan
                            </label>

                            <input
                                value={solution.required_keywords.join(", ")}
                                onChange={(event) =>
                                    updateSolution(index, {
                                        required_keywords: event.target.value
                                            .split(",")
                                            .map((keyword) => keyword.trim())
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
    );
}
