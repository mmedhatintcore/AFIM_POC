export type ApiEnvelope<T> = { data: T; message?: string };

export type Paginated<T> = {
  data: T[];
  links: {
    first: string;
    last: string;
    prev: string | null;
    next: string | null;
  };
  meta: {
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
    from: number | null;
    to: number | null;
  };
};

export const unwrap = <T>(envelope: ApiEnvelope<T>): T => envelope.data;
