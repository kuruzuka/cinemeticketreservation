import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface Booking {
    id: number
    customer_name: string
    schedule: Schedule
    seats: string[]  // Array of seat numbers like ["A1", "B2", "C5"]
    seat_ids: number[]
    booking_count: number
    created_at?: string
}

export interface Genre {
  id: number
  name: string
}

export interface Movie {
  id: number
  title: string
  author: string;
  director: string;
  genre: Genre
}

export interface Cinema {
  id: number
  name: string
}

export interface City {
  id: number
  name: string
}

export interface Timeslot {
  id: number
  start_time: string
  end_time: string
}

export interface Schedule {
  id: number
  movie: Movie
  cinema: Cinema
  city: City
  timeslot: Timeslot
  show_date: string;
}

export interface Customer {
    id: number;
    name: string;
}

export interface Seat {
    id: number;
    seat_number: string;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
