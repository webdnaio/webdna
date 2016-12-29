<?php

namespace WebDNA\Bundle\AppBundle\Event;

/**
 * Class AnalysisEvents
 * @package WebDNA\Bundle\AppBundle\Event
 */
final class AnalysisEvents
{
    const ANALYSIS_WAS_STARTED = 'analysis.started';
    const ANALYSIS_WAS_FINISHED = 'analysis.finished';
    const ANALYSIS_PAGE_WAS_CREATED = 'analysis.page.created';
    const ANALYSIS_PAGE_CLASSIFIED_NEGATIVE = 'analysis.page.classified.negative';
    const ANALYSIS_PAGE_CLASSIFIED_POSITIVE = 'analysis.page.classified.positive';
    const ANALYSIS_PAGE_CLASSIFIED_SUSPICIOUS = 'analysis.page.classified.suspicious';
    const ANALYSIS_PAGE_CLASSIFIED_UNCLASSIFIED = 'analysis.page.classified.unclassified';
    const ANALYSIS_PAGE_CLASSIFIED_UNKNOWN = 'analysis.page.classified.unknown';
}
