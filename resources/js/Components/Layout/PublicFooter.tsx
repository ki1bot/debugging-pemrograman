export default function PublicFooter() {
    const currentYear = new Date().getFullYear();

    return (
        <footer className="mt-20 overflow-hidden border-t-[3px] border-black bg-[#111111] text-white">
            <div className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
                <div className="relative border-[3px] border-white bg-[#191919] p-6 shadow-[7px_7px_0_#ffd93d] sm:p-8 lg:p-10">
                    <div
                        aria-hidden="true"
                        className="absolute right-5 top-5 h-5 w-5 border-2 border-black bg-[#ff6b6b] shadow-[3px_3px_0_#ffd93d] sm:h-7 sm:w-7"
                    />

                    <div
                        aria-hidden="true"
                        className="absolute bottom-6 right-20 hidden h-4 w-14 -rotate-6 border-2 border-black bg-[#7dd3fc] lg:block"
                    />

                    <div className="grid gap-10 lg:grid-cols-[1.25fr_0.75fr] lg:items-end">
                        <div>
                            <div className="inline-flex -rotate-1 border-[3px] border-black bg-[#ffd93d] px-4 py-2 text-sm font-black uppercase tracking-[0.18em] text-black shadow-[4px_4px_0_#ffffff]">
                                Platform Debugging
                            </div>

                            <p className="mt-7 text-5xl font-black leading-none tracking-[-0.07em] sm:text-6xl">
                                BUG
                                <span className="text-[#ffd93d]">HUNT</span>
                            </p>

                            <p className="mt-5 max-w-2xl text-base font-semibold leading-7 text-neutral-300 sm:text-lg">
                                Tempat belajar memahami kesalahan program,
                                menemukan sumber masalah, dan memperbaiki kode
                                secara terstruktur.
                            </p>
                        </div>

                        <div className="border-l-0 border-white/30 lg:border-l-2 lg:pl-8">
                            <p className="text-xs font-black uppercase tracking-[0.28em] text-[#ffd93d]">
                                Proses debugging
                            </p>

                            <div className="mt-5 space-y-4">
                                <div className="flex items-center gap-4">
                                    <span className="grid h-9 w-9 shrink-0 place-items-center border-2 border-white bg-[#ff6b6b] font-black text-black">
                                        01
                                    </span>

                                    <p className="font-bold text-neutral-100">
                                        Baca kode yang bermasalah
                                    </p>
                                </div>

                                <div className="flex items-center gap-4">
                                    <span className="grid h-9 w-9 shrink-0 place-items-center border-2 border-white bg-[#ffd93d] font-black text-black">
                                        02
                                    </span>

                                    <p className="font-bold text-neutral-100">
                                        Temukan sumber kesalahan
                                    </p>
                                </div>

                                <div className="flex items-center gap-4">
                                    <span className="grid h-9 w-9 shrink-0 place-items-center border-2 border-white bg-[#7dd3fc] font-black text-black">
                                        03
                                    </span>

                                    <p className="font-bold text-neutral-100">
                                        Perbaiki dan pahami solusinya
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="mt-10 flex flex-col gap-3 border-t-2 border-white/20 pt-6 text-sm font-bold text-neutral-400 sm:flex-row sm:items-center sm:justify-between">
                    <p>
                        © {currentYear} BugHunt. Seluruh hak cipta dilindungi.
                    </p>

                    <p className="text-neutral-300">Baca. Temukan. Perbaiki.</p>
                </div>
            </div>
        </footer>
    );
}
