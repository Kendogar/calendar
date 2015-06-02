<?php

namespace Dhbw\CalendarBundle\Controller;

use DOMDocument;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dhbw\CalendarBundle\Entity\Date;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;

class CalendarController extends Controller
{
    public function indexAction()
    {
        return $this->render('DhbwCalendarBundle:Calendar:index.html.twig');
    }

    public function createAction(Request $request)
    {
        // create a task and give it some dummy data for this example
        $date = new Date();

        $form = $this->createFormBuilder($date)
            ->add('StartDate', 'date')
            ->add('EndDate', 'date')
            ->add('StartTime', 'time')
            ->add('EndTime', 'time')
            ->add('Title', 'text')
            ->add('Description', 'textarea')
            ->add('save', 'submit', array('label' => 'Create Task'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($date);
            $em->flush();

            return $this->redirectToRoute('findDates');
        }

        return $this->render('DhbwCalendarBundle:Calendar:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function findAction(Request $request){

        $date = new Date();

        $form = $this->createFormBuilder($date)
            ->add('StartDate', 'date')
            ->add('EndDate', 'date')
            ->add('save', 'submit', array('label' => 'Find Tasks'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {


            return $this->redirectToRoute('displayDates', array('start' => $date->getStartDate()->format('Y-m-d'), 'end' => $date->getEndDate()->format('Y-m-d')));
        }

        $dates = $this->getDoctrine()
            ->getRepository('DhbwCalendarBundle:Date')
            ->findAll();

        if (!$dates) {
            throw $this->createNotFoundException(
                'No date found'
            );
        }

        return $this->render('DhbwCalendarBundle:Calendar:find.html.twig', array(
            'form' => $form->createView(),
        ));
        /*return $this->render('DhbwCalendarBundle:Calendar:calendar.html.twig',
            array(
                "allDates" => $dates));*/
    }

    public function displayAction(Request $request){
        $start = date_create($request->query->get('start'));
        $end = date_create($request->query->get('end'));

        if ($end < $start ){
            $end = $start;
        }

        $dates = $this->getDoctrine()
            ->getRepository('DhbwCalendarBundle:Date')
            ->findAll();

        $datesToShow = array();


        foreach ($dates as $date){
            if ($date->getStartDate() >= $start && $date->getEndDate() <= $end){
                array_push($datesToShow, $date);
            }
        }

        $xml = new DOMDocument("1.0");
        //$xml->preserveWhiteSpace= false;
        $xml->formatOutput = true;
        $xml_terminkalender = $xml->createElement("terminkalender");

        foreach ($datesToShow as $date) {

            $xml_termin = $xml->createElement("termin");
            $xml_id = $xml->createElement("id", $date->getID());
            $xml_datum = $xml->createElement("datum");
            $xml_start = $xml->createElement("start",$date->getStartDate()->format('d.m.Y'));
            $xml_ende = $xml->createElement("ende",$date->getEndDate()->format('d.m.Y'));
            $xml_zeit = $xml->createElement("zeit");
            $xml_startzeit = $xml->createElement("startzeit",$date->getStartTime()->format('H:m'));
            $xml_endzeit = $xml->createElement("endzeit",$date->getEndTime()->format('H:m'));
            $xml_titel = $xml->createElement("titel",$date->getTitle());
            $xml_beschreibung = $xml->createElement("beschreibung",$date->getDescription());

            $xml->appendChild( $xml_terminkalender );
            $xml_terminkalender->appendChild($xml_termin);
            $xml_termin->appendChild($xml_id);
            $xml_termin->appendChild($xml_datum);
            $xml_datum->appendChild($xml_start);
            $xml_datum->appendChild($xml_ende);
            $xml_termin->appendChild($xml_zeit);
            $xml_zeit->appendChild($xml_startzeit);
            $xml_zeit->appendChild($xml_endzeit);
            $xml_termin->appendChild($xml_titel);
            $xml_termin->appendChild($xml_beschreibung);
        }

        $xml->save("TerminStrukturBeta.xml");



        /*return $this->render('DhbwCalendarBundle:Calendar:display.html.twig', array(
            "dates" => $datesToShow
        ));*/

        return $this->render('DhbwCalendarBundle:Calendar:display.html.php');

    }
}
