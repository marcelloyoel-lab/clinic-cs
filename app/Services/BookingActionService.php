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
                'url' => route('start-consultation', $booking->consultation),
            ];
        }

        if ($this->canPayConsultation($booking, $user)) {
            $actions[] = [
                'key' => 'payment-consultation',
                'label' => 'Payment',
                'icon' => 'bx-credit-card',
                'id' => $booking->consultation->id,
                'url' => route('consultation-payment', $booking->consultation),
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
                'key' => 'cancel-consultation',
                'label' => 'Cancel',
                'icon' => 'bx-x-circle',
                'id' => $booking->consultation->id,
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

    private function canPayConsultation(Booking $booking, User $user): bool
    {
        if (! $booking->consultation) {
            return false;
        }

        // Only Cashier and Superadmin can process payments
        if (
            ! $user->hasRole('Cashier')
            && ! $user->hasRole('Superadmin')
        ) {
            return false;
        }

        // Check if Booking status is "In Progress" (assuming 1 or your specific Enum state)
        // AND Consultation status is "Payment"
        return $booking->status === \App\Enums\BookingStatus::IN_PROGRESS // adjust to match your exact enum property/value
            && $booking->consultation->status === ConsultationStatus::PAYMENT; // adjust to match your exact enum property/value
    }
}