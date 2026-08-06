import {
    Binary,
    Braces,
    Bug,
    Code2,
    Cpu,
    GitBranch,
    SearchCode,
    Sparkles,
    SquareTerminal,
    Wrench,
} from "lucide-react";

export default function SiteBackdrop() {
    return (
        <div
            aria-hidden="true"
            className="pointer-events-none fixed inset-0 z-0 overflow-hidden"
        >
            <span
                className="site-backdrop-icon absolute left-[3%] top-[16%] hidden h-12 w-12 place-items-center rounded-2xl border border-[#21162f]/10 bg-[#fff2b8]/70 text-[#21162f]/55 shadow-lg sm:grid"
                style={{ "--site-rotation": "-8deg" } as React.CSSProperties}
            >
                <Bug className="h-5 w-5" strokeWidth={2.4} />
            </span>

            <span
                className="site-backdrop-icon absolute left-[12%] top-[55%] hidden h-11 w-11 place-items-center rounded-full border border-[#21162f]/10 bg-[#d4f8e5]/65 text-[#21162f]/50 shadow-lg lg:grid"
                style={{ "--site-rotation": "10deg" } as React.CSSProperties}
            >
                <Braces className="h-5 w-5" strokeWidth={2.4} />
            </span>

            <span
                className="site-backdrop-icon absolute bottom-[12%] left-[5%] hidden h-14 w-14 place-items-center rounded-2xl border border-[#21162f]/10 bg-[#e5ddff]/65 text-[#21162f]/50 shadow-lg xl:grid"
                style={{ "--site-rotation": "-6deg" } as React.CSSProperties}
            >
                <GitBranch className="h-6 w-6" strokeWidth={2.3} />
            </span>

            <span
                className="site-backdrop-icon absolute left-[31%] top-[9%] hidden h-10 w-10 place-items-center rounded-xl border border-[#21162f]/10 bg-white/60 text-[#21162f]/40 shadow-lg xl:grid"
                style={{ "--site-rotation": "12deg" } as React.CSSProperties}
            >
                <Code2 className="h-5 w-5" strokeWidth={2.3} />
            </span>

            <span
                className="site-backdrop-icon absolute right-[31%] top-[23%] hidden h-12 w-12 place-items-center rounded-2xl border border-[#21162f]/10 bg-[#d5f0ff]/60 text-[#21162f]/45 shadow-lg xl:grid"
                style={{ "--site-rotation": "-12deg" } as React.CSSProperties}
            >
                <SquareTerminal className="h-6 w-6" strokeWidth={2.3} />
            </span>

            <span
                className="site-backdrop-icon absolute bottom-[22%] right-[34%] hidden h-10 w-10 place-items-center rounded-full border border-[#21162f]/10 bg-[#ffd2df]/65 text-[#21162f]/45 shadow-lg lg:grid"
                style={{ "--site-rotation": "7deg" } as React.CSSProperties}
            >
                <SearchCode className="h-5 w-5" strokeWidth={2.3} />
            </span>

            <span
                className="site-backdrop-icon absolute right-[7%] top-[12%] hidden h-12 w-12 place-items-center rounded-full border border-[#21162f]/10 bg-[#e5ddff]/65 text-[#21162f]/45 shadow-lg md:grid"
                style={{ "--site-rotation": "9deg" } as React.CSSProperties}
            >
                <Sparkles className="h-5 w-5" strokeWidth={2.4} />
            </span>

            <span
                className="site-backdrop-icon absolute right-[3%] top-[48%] hidden h-14 w-14 place-items-center rounded-2xl border border-[#21162f]/10 bg-[#fff2b8]/60 text-[#21162f]/45 shadow-lg lg:grid"
                style={{ "--site-rotation": "-8deg" } as React.CSSProperties}
            >
                <Cpu className="h-6 w-6" strokeWidth={2.3} />
            </span>

            <span
                className="site-backdrop-icon absolute bottom-[7%] right-[12%] hidden h-11 w-11 place-items-center rounded-xl border border-[#21162f]/10 bg-[#d4f8e5]/60 text-[#21162f]/45 shadow-lg xl:grid"
                style={{ "--site-rotation": "13deg" } as React.CSSProperties}
            >
                <Wrench className="h-5 w-5" strokeWidth={2.3} />
            </span>

            <span
                className="site-backdrop-icon absolute bottom-[6%] left-[42%] hidden h-10 w-10 place-items-center rounded-full border border-[#21162f]/10 bg-white/55 text-[#21162f]/40 shadow-lg xl:grid"
                style={{ "--site-rotation": "-9deg" } as React.CSSProperties}
            >
                <Binary className="h-5 w-5" strokeWidth={2.3} />
            </span>
        </div>
    );
}
