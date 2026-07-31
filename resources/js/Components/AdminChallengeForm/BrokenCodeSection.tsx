import CodeEditor from "@/Components/CodeEditor";
import InputError from "@/Components/InputError";
import {
    ChallengeFormData,
    ChallengeFormErrors,
    SetChallengeFormData,
} from "@/Components/AdminChallengeForm/types";

type BrokenCodeSectionProps = {
    data: ChallengeFormData;
    errors: ChallengeFormErrors;
    selectedCategory: string;
    setData: SetChallengeFormData;
};

export default function BrokenCodeSection({
    data,
    errors,
    selectedCategory,
    setData,
}: BrokenCodeSectionProps) {
    return (
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
                        value={data.buggy_line}
                        onChange={(event) =>
                            setData("buggy_line", Number(event.target.value))
                        }
                        className="nb-input"
                    />

                    <InputError message={errors.buggy_line} className="mt-3" />
                </div>
            </div>

            <div className="mt-6">
                <CodeEditor
                    value={data.broken_code}
                    onChange={(value) => setData("broken_code", value)}
                    language={selectedCategory}
                    minHeight="420px"
                />

                <InputError message={errors.broken_code} className="mt-4" />
            </div>
        </section>
    );
}
