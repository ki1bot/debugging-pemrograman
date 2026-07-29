import BasicInformationSection from "@/Components/AdminChallengeForm/BagianInformasiDasar";
import BrokenCodeSection from "@/Components/AdminChallengeForm/BagianKodeRusak";
import ExplanationSection from "@/Components/AdminChallengeForm/BagianPenjelasan";
import FormActions from "@/Components/AdminChallengeForm/AksiFormulir";
import HintsSection from "@/Components/AdminChallengeForm/BagianPetunjuk";
import SolutionsSection from "@/Components/AdminChallengeForm/BagianSolusi";
import {
    AdminChallengeFormProps,
    ChallengeFormErrors,
} from "@/Components/AdminChallengeForm/tipe";
import { useAdminChallengeForm } from "@/Components/AdminChallengeForm/gunakanFormulirTantanganAdmin";

export default function AdminChallengeForm({
    categories,
    difficulties,
    challenge,
}: AdminChallengeFormProps) {
    const {
        form,
        editing,
        selectedCategory,
        changeDifficulty,
        updateHint,
        addHint,
        removeHint,
        updateSolution,
        addSolution,
        removeSolution,
        submit,
    } = useAdminChallengeForm(categories, difficulties, challenge);

    const errors = form.errors as ChallengeFormErrors;

    return (
        <form onSubmit={submit} className="space-y-8">
            <BasicInformationSection
                data={form.data}
                errors={errors}
                categories={categories}
                difficulties={difficulties}
                setData={form.setData}
                changeDifficulty={changeDifficulty}
            />

            <BrokenCodeSection
                data={form.data}
                errors={errors}
                selectedCategory={selectedCategory}
                setData={form.setData}
            />

            <ExplanationSection
                data={form.data}
                errors={errors}
                setData={form.setData}
            />

            <HintsSection
                hints={form.data.hints}
                errors={errors}
                addHint={addHint}
                removeHint={removeHint}
                updateHint={updateHint}
            />

            <SolutionsSection
                solutions={form.data.solutions}
                errors={errors}
                selectedCategory={selectedCategory}
                addSolution={addSolution}
                removeSolution={removeSolution}
                updateSolution={updateSolution}
            />

            <FormActions processing={form.processing} editing={editing} />
        </form>
    );
}
