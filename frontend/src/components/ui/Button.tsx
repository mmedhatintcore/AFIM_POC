import { cva, type VariantProps } from "class-variance-authority";
import type { ButtonHTMLAttributes } from "react";
import { cn } from "@/lib/utils/cn";

export const buttonVariants = cva(
  "inline-flex cursor-pointer items-center justify-center gap-2 font-semibold transition-all duration-300 ease-out-soft focus-visible:outline-2 focus-visible:outline-accent disabled:pointer-events-none disabled:opacity-50",
  {
    variants: {
      variant: {
        cta: "rounded-3xl bg-accent text-accent-on shadow-none hover:-translate-y-0.5 hover:rounded-lg hover:shadow-elev-2",
        ghost:
          "rounded-3xl border border-border text-foreground hover:rounded-lg hover:border-accent hover:text-accent",
        ghostOnNavy:
          "rounded-3xl border border-white/30 text-white hover:rounded-lg hover:border-accent hover:text-accent",
        link: "text-accent hover:text-accent-dark",
      },
      size: {
        sm: "px-4 py-1.5 text-sm",
        md: "px-5 py-2.5 text-sm",
        lg: "px-7 py-3 text-base",
      },
    },
    defaultVariants: { variant: "cta", size: "md" },
  },
);

type ButtonProps = ButtonHTMLAttributes<HTMLButtonElement> &
  VariantProps<typeof buttonVariants>;

export function Button({ className, variant, size, ...props }: ButtonProps) {
  return (
    <button
      className={cn(buttonVariants({ variant, size }), className)}
      {...props}
    />
  );
}
