import InputError from "@/Components/InputError";
import {
    ChallengeFormErrors,
    HintForm,
} from "@/Components/AdminChallengeForm/types";

type HintsSectionProps = {
    hints: HintForm[];
    errors: ChallengeFormErrors;
    addHint: () => void;
    removeHint: (index: number) => void;
    updateHint: (
        index: number,
        key: keyof HintForm,
        value: string | number,
    ) => void;
};

export default function HintsSection({
    hints,
    errors,
    addHint,
    removeHint,
    updateHint,
}: HintsSectionProps) {
    return (
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
                    disabled={hints.length >= 5}
                    className="nb-button bg-[#9ef0b8] text-sm"
                >
                    Tambah Hint
                </button>
            </div>

            <div className="mt-6 grid gap-5">
                {hints.map((hint, index) => (
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
                                disabled={hints.length <= 1}
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
                                    message={errors[`hints.${index}.content`]}
                                    className="mt-3"
                                />
                            </div>

                            <div>
                                <label className="nb-label">Penalti %</label>

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
    );
}
