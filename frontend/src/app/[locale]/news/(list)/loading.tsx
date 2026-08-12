import { Container } from "@/components/ui/Container";
import { Skeleton } from "@/components/ui/Skeleton";

export default function NewsLoading() {
  return (
    <Container className="py-14">
      <Skeleton className="mb-3 h-10 w-56" />
      <Skeleton className="mb-10 h-5 w-96 max-w-full" />
      <div className="mb-9 flex flex-wrap gap-2.5">
        {Array.from({ length: 4 }, (_, i) => (
          <Skeleton key={i} className="h-10 w-32 rounded-full" />
        ))}
      </div>
      <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        {Array.from({ length: 6 }, (_, i) => (
          <Skeleton key={i} className="h-56" />
        ))}
      </div>
    </Container>
  );
}
