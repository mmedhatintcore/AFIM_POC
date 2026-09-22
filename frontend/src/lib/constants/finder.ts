/**
 * "Find your service" — the recommendation logic for the 3-question wizard.
 * The questions/options themselves are admin-managed (see the "Find
 * Services" admin section) and fetched from `GET /v1/finder/questions`;
 * this file only holds the fixed tag → result mapping.
 */

export type FinderResultKey =
  | "subscription"
  | "liquidity"
  | "portfolio"
  | "funds";

export function recommend(answers: string[]): FinderResultKey {
  // AFIM no longer offers Promotion & Underwriting — a company seeking
  // capital-raising is closest served today by Portfolio Management.
  if (answers[0] === "raise" || answers[1] === "capital") return "portfolio";
  if (answers[2] === "exec") return "subscription";
  if (answers[0] === "inst" || answers[2] === "managed") {
    return answers[1] === "preserve" ? "liquidity" : "portfolio";
  }
  if (answers[1] === "preserve") return "liquidity";
  return "funds"; // grow / islamic / pooled individuals
}
