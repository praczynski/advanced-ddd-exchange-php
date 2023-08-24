<?php

namespace App\Negotiation\Ui;

use App\Negotiation\Application\RiskAssessmentApplicationService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use OpenApi\Annotations as OA;
class RiskAssessmentController
{
    private RiskAssessmentApplicationService $riskAssessmentApplicationService;
    private SerializerInterface $serializer;

    public function __construct(RiskAssessmentApplicationService $riskAssessmentApplicationService, SerializerInterface $serializer)
    {
        $this->riskAssessmentApplicationService = $riskAssessmentApplicationService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/riskassessment/{riskAssessmentNumber}/{riskLevel}", name="change_risk_assessment_risk_level", methods={"POST"})
     */
    public function changeRiskAssessmentRiskLevel(string $riskAssessmentNumber, string $riskLevel): Response
    {
        $changeRiskAssessmentRiskLevelStatus = $this->riskAssessmentApplicationService->changeRiskAssessmentRiskLevel($riskAssessmentNumber, $riskLevel);
        $jsonString = $this->serializer->serialize($changeRiskAssessmentRiskLevelStatus, 'json');
        return new Response($jsonString, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}