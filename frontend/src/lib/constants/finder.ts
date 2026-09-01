/**
 * The 3-question service finder — ported from the reference page's `wizard`
 * object and `recommend()` logic. Labels/descriptions live in the message
 * catalogs under `Finder.q1..q3`; this file holds structure only.
 */

export type FinderQuestionId = "q1" | "q2" | "q3";

export type FinderOption = {
  tag: string;
  icon: string;
};

export type FinderQuestion = {
  id: FinderQuestionId;
  options: FinderOption[];
};

export const FINDER_QUESTIONS: FinderQuestion[] = [
  {
    id: "q1",
    options: [
      { tag: "ind", icon: "user" },
      { tag: "inst", icon: "corp" },
      { tag: "raise", icon: "trend" },
    ],
  },
  {
    id: "q2",
    options: [
      { tag: "preserve", icon: "shield" },
      { tag: "grow", icon: "sprout" },
      { tag: "islamic", icon: "crescent" },
      { tag: "capital", icon: "coins" },
    ],
  },
  {
    id: "q3",
    options: [
      { tag: "managed", icon: "briefcase" },
      { tag: "pooled", icon: "pie" },
      { tag: "exec", icon: "swap" },
    ],
  },
];

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
