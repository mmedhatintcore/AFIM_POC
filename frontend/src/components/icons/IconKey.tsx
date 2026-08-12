import {
  ArrowLeftRight,
  Banknote,
  Briefcase,
  Building2,
  ChartLine,
  ChartPie,
  CircleCheck,
  Clock,
  Coins,
  Compass,
  Eye,
  Gauge,
  Landmark,
  LayoutGrid,
  Lightbulb,
  Lock,
  MoonStar,
  Rocket,
  Scale,
  Shield,
  Sprout,
  Target,
  TrendingUp,
  User,
  WavesHorizontal,
  Zap,
  type LucideIcon,
} from "lucide-react";
import { cn } from "@/lib/utils/cn";

/**
 * Maps the API's icon keys (survey options, section items, FAQs) to
 * lucide-react icons. Unknown keys fall back to a target icon.
 */
const ICON_MAP: Record<string, { icon: LucideIcon; className?: string }> = {
  shield: { icon: Shield },
  payout: { icon: Banknote },
  scales: { icon: Scale },
  launch: { icon: Rocket },
  user: { icon: User },
  corp: { icon: Building2 },
  crescent: { icon: MoonStar },
  check: { icon: CircleCheck },
  lock: { icon: Lock },
  waves: { icon: WavesHorizontal },
  chartDip: { icon: ChartLine },
  bolt: { icon: Zap },
  grid: { icon: LayoutGrid },
  target: { icon: Target },
  gaugeLo: { icon: Gauge, className: "text-up" },
  gaugeMd: { icon: Gauge, className: "text-warn" },
  gaugeHi: { icon: Gauge, className: "text-down" },
  trend: { icon: TrendingUp },
  sprout: { icon: Sprout },
  coins: { icon: Coins },
  briefcase: { icon: Briefcase },
  pie: { icon: ChartPie },
  swap: { icon: ArrowLeftRight },
  compass: { icon: Compass },
  bulb: { icon: Lightbulb },
  clock: { icon: Clock },
  eye: { icon: Eye },
  institution: { icon: Landmark },
  bank: { icon: Landmark },
};

export function IconKey({
  name,
  className,
}: {
  name?: string | null;
  className?: string;
}) {
  const entry = (name && ICON_MAP[name]) || { icon: Target };
  const Icon = entry.icon;
  return (
    <Icon
      className={cn("size-5", entry.className, className)}
      aria-hidden="true"
      strokeWidth={1.8}
    />
  );
}
