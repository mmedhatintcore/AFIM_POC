import { CircleAlert } from "lucide-react";

export function EmptyState({ message }: { message: string }) {
  return (
    <div className="flex flex-col items-center gap-3 rounded-card-lg border border-dashed border-border bg-surface px-6 py-14 text-center">
      <CircleAlert className="size-6 text-muted" aria-hidden="true" />
      <p className="max-w-md text-sm text-soft">{message}</p>
    </div>
  );
}
