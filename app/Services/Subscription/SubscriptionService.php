<?php

namespace App\Services\Subscription;

use App\Actions\Subscription\ValidatePartnerAction;
use App\Actions\Subscription\CreateSubscriberAction;
use App\Actions\Subscription\ValidateServiceOfferAction;
use App\Actions\Subscription\CreateSubscriptionAction;
use App\Actions\Subscription\CancelSubscriptionAction;
use App\Actions\Subscription\GetSubscriptionStatusAction;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    protected ValidatePartnerAction $validatePartnerAction;
    protected CreateSubscriberAction $createSubscriberAction;
    protected ValidateServiceOfferAction $validateServiceOfferAction;
    protected CreateSubscriptionAction $createSubscriptionAction;
    protected CancelSubscriptionAction $cancelSubscriptionAction;
    protected GetSubscriptionStatusAction $getSubscriptionStatusAction;

    public function __construct(
        ValidatePartnerAction $validatePartnerAction,
        CreateSubscriberAction $createSubscriberAction,
        ValidateServiceOfferAction $validateServiceOfferAction,
        CreateSubscriptionAction $createSubscriptionAction,
        CancelSubscriptionAction $cancelSubscriptionAction,
        GetSubscriptionStatusAction $getSubscriptionStatusAction
    ) {
        $this->validatePartnerAction = $validatePartnerAction;
        $this->createSubscriberAction = $createSubscriberAction;
        $this->validateServiceOfferAction = $validateServiceOfferAction;
        $this->createSubscriptionAction = $createSubscriptionAction;
        $this->cancelSubscriptionAction = $cancelSubscriptionAction;
        $this->getSubscriptionStatusAction = $getSubscriptionStatusAction;
    }

    /**
     * Traite l'opt-in d'un abonné à un service
     */
    public function optin(array $data, string $partner_id): array
    {
        DB::beginTransaction();

        try {
            // 1. Valider le partenaire
            $partner = $this->validatePartnerAction->execute($partner_id);

            // 2. Valider l'offre de service
            $this->validateServiceOfferAction->execute(
                $data['service_offer_id'], 
                $partner->id
            );

            // 3. Créer ou récupérer l'abonné
            $subscriber = $this->createSubscriberAction->execute($data['msisdn']);

            // 4. Créer l'abonnement
            $subscriptionResult = $this->createSubscriptionAction->execute([
                'subscriber_id' => $subscriber->id,
                'service_offer_id' => $data['service_offer_id'],
                'canal' => $data['canal'] ?? 'api',
            ]);

            DB::commit();

            Log::info('Subscription opt-in successful', $subscriptionResult);

            return $subscriptionResult;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Subscription opt-in failed', [
                'partner_id' => $partner_id,
                'msisdn' => $data['msisdn'] ?? null,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Traite l'opt-out d'un abonné d'un service
     */
    public function optout(array $data, string $partner_id): array
    {
        DB::beginTransaction();

        try {
            // 1. Valider le partenaire
            $partner = $this->validatePartnerAction->execute($partner_id);

            // 2. Annuler l'abonnement
            $result = $this->cancelSubscriptionAction->execute([
                'msisdn' => $data['msisdn'],
                'service_offer_id' => $data['service_offer_id'] ?? null,
                'partner_id' => $partner->id,
            ]);

            DB::commit();

            Log::info('Subscription opt-out successful', [
                'partner_id' => $partner_id,
                'msisdn' => $data['msisdn'],
                'unsubscribed_count' => $result['unsubscribed_count'],
                'service_offer_id' => $data['service_offer_id'] ?? null,
            ]);

            return $result;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Subscription opt-out failed', [
                'partner_id' => $partner_id,
                'msisdn' => $data['msisdn'] ?? null,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Récupère le statut des abonnements d'un abonné
     */
    public function getStatus(array $data, string $partner_id): Subscription
    {
        try {
            // 1. Valider le partenaire
            $partner = $this->validatePartnerAction->execute($partner_id);

            // 2. Récupérer le statut des abonnements
            $result = $this->getSubscriptionStatusAction->execute([
                'msisdn' => $data['msisdn'],
                'partner_id' => $partner->id,
            ]);

            return $result;

        } catch (\Exception $e) {
            Log::error('Subscription status check failed', [
                'partner_id' => $partner_id,
                'msisdn' => $data['msisdn'] ?? null,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}