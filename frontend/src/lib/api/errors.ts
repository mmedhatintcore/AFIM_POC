import { AxiosError } from "axios";

export class ApiError extends Error {
  constructor(
    public readonly status: number,
    public readonly code: string,
    message: string,
    public readonly fieldErrors: Record<string, string[]> = {},
  ) {
    super(message);
    this.name = "ApiError";
  }
}

type ErrorBody = { message?: string; errors?: Record<string, string[]> };

export function normalizeError(error: unknown): ApiError {
  if (error instanceof ApiError) return error;

  if (error instanceof AxiosError) {
    const response = error.response;
    if (!response) {
      return new ApiError(0, "network_error", "Network error");
    }
    const body = (response.data ?? {}) as ErrorBody;
    return new ApiError(
      response.status,
      mapStatusToCode(response.status),
      body.message ?? "Request failed",
      body.errors ?? {},
    );
  }

  return new ApiError(
    0,
    "unknown_error",
    error instanceof Error ? error.message : "Unknown error",
  );
}

export function isApiError(error: unknown): error is ApiError {
  return error instanceof ApiError;
}

function mapStatusToCode(status: number): string {
  switch (status) {
    case 401:
      return "unauthenticated";
    case 403:
      return "forbidden";
    case 404:
      return "not_found";
    case 409:
      return "conflict";
    case 422:
      return "validation";
    case 429:
      return "throttled";
    default:
      return status >= 500 ? "server_error" : "request_failed";
  }
}
