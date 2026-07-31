import InputError from "@/Components/InputError";
import {
    ChallengeFormData,
    ChallengeFormErrors,
    SetChallengeFormData,
} from "@/Components/AdminChallengeForm/types";

type ExplanationSectionProps = {
    data: ChallengeFormData;
    errors: ChallengeFormErrors;
    setData: SetChallengeFormData;
};

export default function ExplanationSection({
    data,
    errors,
    setData,
}: ExplanationSectionProps) {
    return (
        <section className="nb-card bg-[#ffd93d] p-6">
            <h2 className="text-2xl font-black">Pembahasan Lengkap</h2>

            <textarea
                value={data.explanation}
                onChange={(event) => setData("explanation", event.target.value)}
                className="nb-input mt-6 min-h-48 resize-y"
                placeholder="Jelaskan penyebab bug dan alasan solusi bekerja."
            />

            <InputError message={errors.explanation} className="mt-3" />
        </section>
    );
}
