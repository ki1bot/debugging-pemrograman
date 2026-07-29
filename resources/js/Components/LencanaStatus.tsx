import { SubmissionStatus } from "@/types";

type StatusBadgeProps = {
    status: SubmissionStatus | "draft" | "published" | "inactive";
};

const labels: Record<StatusBadgeProps["status"], string> = {
    incorrect: "Belum tepat",
    partially_correct: "Sebagian benar",
    completed: "Selesai",
    draft: "Draft",
    published: "Terbit",
    inactive: "Nonaktif",
};

const backgrounds: Record<StatusBadgeProps["status"], string> = {
    incorrect: "bg-[#ff9c9c]",
    partially_correct: "bg-[#ffcf70]",
    completed: "bg-[#9ef0b8]",
    draft: "bg-[#d6d6d6]",
    published: "bg-[#9ed8ff]",
    inactive: "bg-[#c4b5fd]",
};

export default function StatusBadge({ status }: StatusBadgeProps) {
    return (
        <span className={`nb-badge ${backgrounds[status]}`}>
            {labels[status]}
        </span>
    );
}
