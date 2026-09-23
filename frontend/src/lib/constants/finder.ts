import type { FinderApiQuestion, FinderResultKey } from "@/types/api";

const RESULT_KEYS: FinderResultKey[] = [
  "subscription",
  "liquidity",
  "portfolio",
  "funds",
];

/**
 * "Find your service" — sums each answered option's points per recommendation
 * and returns the highest-scoring one. Questions/options (and their points)
 * are fully admin-managed (see the "Find Services" admin section), so this
 * has to work for any number of questions/options rather than assuming a
 * fixed 3-question shape.
 */
export function recommend(
  questions: FinderApiQuestion[],
  answers: Record<number, number>,
): FinderResultKey {
  const scores: Record<FinderResultKey, number> = {
    subscription: 0,
    liquidity: 0,
    portfolio: 0,
    funds: 0,
  };

  for (const question of questions) {
    const option = question.options[answers[question.id]];
    if (!option) continue;
    for (const key of RESULT_KEYS) {
      scores[key] += option.votes[key] ?? 0;
    }
  }

  const [top] = RESULT_KEYS
    .map((key) => [key, scores[key]] as const)
    .sort((a, b) => b[1] - a[1]);

  return top[1] > 0 ? top[0] : "funds";
}
