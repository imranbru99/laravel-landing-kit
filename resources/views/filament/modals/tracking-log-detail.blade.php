<div class="space-y-4 text-xs">
    <div>
        <h4 class="font-bold text-slate-700 dark:text-slate-300 mb-1">Payload Sent:</h4>
        <pre class="bg-slate-900 text-emerald-400 p-3 rounded-xl overflow-x-auto font-mono text-[11px]">{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
    </div>

    <div>
        <h4 class="font-bold text-slate-700 dark:text-slate-300 mb-1">Server Response:</h4>
        <pre class="bg-slate-900 text-slate-200 p-3 rounded-xl overflow-x-auto font-mono text-[11px]">{{ $log->response ?? 'No response logged' }}</pre>
    </div>
</div>
