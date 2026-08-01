export default function PublicFooter() {
    const currentYear = new Date().getFullYear();

    return (
        <footer className="mt-14 overflow-hidden border-t-[3px] border-black bg-[#111111] text-white sm:mt-20">
            <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-12 lg:px-8 lg:py-16">
                <div className="relative border-[3px] border-white bg-[#191919] p-4 shadow-[4px_5px_0_#ffd93d] sm:p-8 sm:shadow-[7px_7px_0_#ffd93d] lg:p-10">
                    <div
                        aria-hidden="true"
                        className="absolute right-4 top-4 h-5 w-5 border-2 border-black bg-[#ff6b6b] shadow-[2px_2px_0_#ffd93d] sm:right-5 sm:top-5 sm:h-7 sm:w-7 sm:shadow-[3px_3px_0_#ffd93d]"
                    />

                    <div
                        aria-hidden="true"
                        className="absolute bottom-6 right-20 hidden h-4 w-14 -rotate-6 border-2 border-black bg-[#7dd3fc] lg:block"
                    />

                    <div className="grid gap-9 lg:grid-cols-[1.25fr_0.75fr] lg:items-end lg:gap-10">
                        <div className="min-w-0">
                            <div className="inline-flex max-w-[calc(100%-1.5rem)] -rotate-1 whitespace-normal border-[3px] border-black bg-[#ffd93d] px-3 py-2 text-left text-xs font-black uppercase leading-5 tracking-[0.12em] text-black shadow-[3px_3px_0_#ffffff] sm:max-w-full sm:px-4 sm:text-sm sm:tracking-[0.18em] sm:shadow-[4px_4px_0_#ffffff]">
                                Platform Latihan Debugging
                            </div>

                            <p className="mt-7 break-words text-3xl font-black leading-[0.95] tracking-[-0.055em] min-[360px]:text-4xl sm:text-6xl sm:tracking-[-0.07em]">
                                DEBUGGING{" "}
                                <span className="block text-[#ffd93d] sm:inline">
                                    PEMROGRAMAN
                                </span>
                            </p>

                            <p className="mt-5 max-w-2xl text-sm font-semibold leading-7 text-neutral-300 sm:text-lg">
                                Latihan interaktif untuk membaca kode, menemukan
                                sumber masalah, menguji perbaikan, dan memahami
                                alasan teknis di balik setiap solusi.
                            </p>
                        </div>

                        <div className="border-l-0 border-white/30 lg:border-l-2 lg:pl-8">
                            <p className="text-xs font-black uppercase tracking-[0.22em] text-[#ffd93d] sm:tracking-[0.28em]">
                                Cara berlatih
                            </p>

                            <div className="mt-5 space-y-4">
                                <div className="flex items-start gap-3 sm:items-center sm:gap-4">
                                    <span className="grid h-9 w-9 shrink-0 place-items-center border-2 border-white bg-[#ff6b6b] font-black text-black">
                                        01
                                    </span>

                                    <p className="font-bold leading-6 text-neutral-100">
                                        Baca kode dan pahami alurnya
                                    </p>
                                </div>

                                <div className="flex items-start gap-3 sm:items-center sm:gap-4">
                                    <span className="grid h-9 w-9 shrink-0 place-items-center border-2 border-white bg-[#ffd93d] font-black text-black">
                                        02
                                    </span>

                                    <p className="font-bold leading-6 text-neutral-100">
                                        Temukan sumber masalah
                                    </p>
                                </div>

                                <div className="flex items-start gap-3 sm:items-center sm:gap-4">
                                    <span className="grid h-9 w-9 shrink-0 place-items-center border-2 border-white bg-[#7dd3fc] font-black text-black">
                                        03
                                    </span>

                                    <p className="font-bold leading-6 text-neutral-100">
                                        Uji perbaikan dan jelaskan alasannya
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="mt-9 flex flex-col gap-3 border-t-2 border-white/20 pt-6 text-left text-sm font-bold text-neutral-400 sm:mt-10 sm:flex-row sm:items-center sm:justify-between">
                    <p>© {currentYear} Debugging Pemrograman.</p>

                    <p className="max-w-xl leading-6 text-neutral-300 sm:text-right">
                        Baca kodenya. Temukan masalahnya. Perbaiki dengan alasan
                        yang jelas.
                    </p>
                </div>
            </div>
        </footer>
    );
}
