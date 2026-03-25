<style>
.ai-page { max-width: 640px; margin: 2rem auto; font-family: system-ui, sans-serif; }
.ai-page h2 { font-size: 1.6rem; margin-bottom: .25rem; }
.ai-page .subtitle { color: #666; margin-bottom: 1.5rem; font-size: .95rem; }

    .ai-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1.75rem; box-shadow: 0 1px 4px rgba(0,0,0,.06); }

        .field { margin-bottom: 1.25rem; }
.field label { display: block; font-weight: 600; font-size: .9rem; margin-bottom: .4rem; color: #374151; }
.field select,
.field input[type="text"] {
            width: 100%; padding: .55rem .8rem; border: 1px solid #d1d5db;
    border-radius: 6px; font-size: .95rem; color: #111;
    transition: border-color .15s;
}
.field select:focus,
.field input[type="text"]:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
.field .hint { font-size: .8rem; color: #9ca3af; margin-top: .3rem; }

.btn-generate {
                        width: 100%; padding: .7rem; background: #6366f1; color: #fff;
    border: none; border-radius: 7px; font-size: 1rem; font-weight: 600;
    cursor: pointer; transition: background .15s;
}
.btn-generate:hover { background: #4f46e5; }
                        .btn-generate:disabled { background: #a5b4fc; cursor: not-allowed; }

                            .nav-links { margin-top: 1rem; display: flex; gap: .75rem; justify-content: center; }
.nav-links a { font-size: .85rem; color: #6366f1; text-decoration: none; }
.nav-links a:hover { text-decoration: underline; }

.alert-box { padding: .8rem 1rem; border-radius: 7px; margin-bottom: 1rem; font-size: .9rem; }
.alert-box.success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
                                    .alert-box.error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
                                        .alert-box.warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
                                            .alert-box ul { margin: 0; padding-left: 1.2rem; }
</style>
