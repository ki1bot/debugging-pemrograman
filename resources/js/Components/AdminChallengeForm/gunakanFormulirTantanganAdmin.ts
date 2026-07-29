import {
    ChallengeFormData,
    EditableChallenge,
    HintForm,
    SolutionForm,
} from "@/Components/AdminChallengeForm/tipe";
import { Category, Difficulty } from "@/types";
import { useForm } from "@inertiajs/react";
import { FormEvent } from "react";

export function useAdminChallengeForm(
    categories: Category[],
    difficulties: Difficulty[],
    challenge?: EditableChallenge,
) {
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

    return {
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
    };
}
