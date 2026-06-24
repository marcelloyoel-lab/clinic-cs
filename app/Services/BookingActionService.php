<?php

namespace App\Services;

use App\Enums\BookingType;
use App\Enums\ConsultationStatus;
use App\Models\Booking;
use App\Models\User;

class BookingActionService
{
    public function getActions(Booking $booking, User $user): array
    {
        return match ($booking->type) {
            BookingType::CONSULTATION => $this->buildConsultationActions($booking, $user),
            BookingType::TREATMENT => $this->buildTreatmentActions($booking, $user),
            default => [],
        };
    }

    private function buildConsultationActions(Booking $booking, User $user): array
    {
        $actions = [];

        if ($this->canStartConsultation($booking, $user)) {
            $actions[] = [
                'key' => 'start-consultation',
                'label' => 'Start Consultation',
                'icon' => 'bx-play-circle',
                'id' => $booking->consultation->id,
                'url'   => $this->getStartConsultationUrl($booking),
            ];
        }

        if ($this->canViewConsultation($booking, $user)) {
            $actions[] = [
                'key' => 'view-consultation',
                'label' => 'Detail',
                'icon' => 'bx-show',
                'id' => $booking->consultation->id,
                'url' => route('view-consultation', $booking->consultation),
            ];
        }

        if ($this->canEditBooking($booking, $user)) {
            $actions[] = [
                'key' => 'edit-booking',
                'label' => 'Edit',
                'icon' => 'bx-edit',
                'id' => $booking->id,
                'url' => route('edit-booking', $booking),
            ];
        }

        if ($this->canCancelBooking($booking, $user)) {
            $actions[] = [
                'key' => 'cancel-booking',
                'label' => 'Cancel',
                'icon' => 'bx-x-circle',
                'id' => $booking->id,
                'url' => route('cancel-booking', $booking),
            ];
        }

        return $actions;
    }

    private function buildTreatmentActions(
        Booking $booking,
        User $user
    ): array {
        return [];
    }

    private function canStartConsultation(
        Booking $booking,
        User $user
    ): bool {
        if (! $booking->consultation) {
            return false;
        }

        if (
            ! $user->hasRole('Doctor')
            && ! $user->hasRole('Superadmin')
        ) {
            return false;
        }

        return $booking->consultation->status === ConsultationStatus::DRAFT;
    }

    private function canViewConsultation(
        Booking $booking,
        User $user
    ): bool {
        if (! $booking->consultation) {
            return false;
        }

        if (
            ! $user->hasRole('Doctor')
            && ! $user->hasRole('Superadmin')
        ) {
            return false;
        }

        return $booking->consultation->status === ConsultationStatus::ON_GOING;
    }

    private function canEditBooking(
        Booking $booking,
        User $user
    ): bool {
        if (! $booking->consultation) {
            return false;
        }

        if (
            ! $user->hasRole('CS')
            && ! $user->hasRole('Superadmin')
        ) {
            return false;
        }

        return $booking->consultation->status === ConsultationStatus::DRAFT;
    }

    private function canCancelBooking(
        Booking $booking,
        User $user
    ): bool {
        if (! $booking->consultation) {
            return false;
        }

        if (
            ! $user->hasRole('Doctor')
            && ! $user->hasRole('CS')
            && ! $user->hasRole('Superadmin')
        ) {
            return false;
        }

        return $booking->consultation->status === ConsultationStatus::DRAFT;
    }

    private function getStartConsultationUrl(Booking $booking): string
    {
        return match ($booking->type) {
            BookingType::CONSULTATION =>
                route('start-consultation', $booking->consultation),

            default =>
                route('dummy-start-treatment', $booking),
        };
    }
}