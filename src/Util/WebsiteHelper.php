<?php

namespace App\Util;

use App\DataProcessor\Website\Manager as WebsiteDataProcessorManager;
use App\Entity\Server;
use App\Entity\Website;
use App\Repository\WebsiteRepository;
use Doctrine\ORM\EntityManagerInterface;

class WebsiteHelper
{
    private EntityManagerInterface $entityManager;

    private WebsiteRepository $websiteRepository;

    private WebsiteDataProcessorManager $websiteDataProcessorManager;

    public function __construct(EntityManagerInterface $entityManager, WebsiteRepository $websiteRepository, WebsiteDataProcessorManager $websiteDataProcessorManager)
    {
        $this->entityManager = $entityManager;
        $this->websiteRepository = $websiteRepository;
        $this->websiteDataProcessorManager = $websiteDataProcessorManager;
    }

    public function buildWebsites(Server $server, array $serverData): ?array
    {
        $data = [];

        // Disable all sites on the server. Note: This will not handle sites being moved from one server to another, but we'll run `console app:server:process --force` regularly to remedy this.
        foreach ($server->getWebsites() as $website) {
            $website->setEnabled(false);
        }

        if (isset($serverData['virtual-hosts']['vhost'])) {
            $projectsData = $this->getProjectsData($serverData);
            foreach ($serverData['virtual-hosts']['vhost'] as $vhost) {
                $conf = $vhost['@text'];
                $data = $this->getVhostData($conf);

                if (isset($data['document_root'], $data['server_names'])) {
                    foreach ($data['server_names'] as $domain) {
                        $website = $this->websiteRepository->findOneBy(['domain' => $domain]);
                        if (null === $website) {
                            $website = new Website();
                        }
                        $website
                            ->setServer($server)
                            ->setEnabled(true)
                            ->setDomain($domain)
                            ->setDocumentRoot($data['document_root'])
                            ->setType(Website::TYPE_UNKNOWN)
                            ->setVersion(Website::VERSION_UNKNOWN)
                        ;

                        foreach ($projectsData as $projectDir => $websiteData) {
                            if (0 === strpos($data['document_root'], $projectDir)) {
                                $website->setSiteRoot($projectDir);
                                $data = $this->websiteDataProcessorManager->process($websiteData);
                                $website->setData($data);
                                break;
                            }
                        }

                        $this->entityManager->persist($website);
                    }
                    $this->entityManager->flush();
                }
            }
        }

        return $data;
    }

    private function getVhostData(string $conf): ?array
    {
        $data = [];

        if (preg_match('/^\s*(?P<key>root|DocumentRoot)\s+(?P<document_root>[^;\s]+)/im', $conf, $matches)) {
            $data['document_root'] = $matches['document_root'];
            if (preg_match_all('/^\s*(?P<key>server_name|Server(?:Name|Alias))\s+(?P<names>[^;\s]+)/im', $conf, $matches)) {
                $serverNames = [];
                foreach ($matches['names'] as $names) {
                    $serverNames[] = preg_split('/\s+/', $names, -1, PREG_SPLIT_NO_EMPTY);
                }
                $data['server_names'] = array_unique(array_merge(...$serverNames));
            }
        }

        return $data ?: null;
    }

    /**
     * Group data by value of project-dir attribute.
     */
    private function getProjectsData(array $serverData): array
    {
        $data = [];

        if (isset($serverData['sites'])) {
            foreach ($serverData['sites'] as $type => $stuff) {
                foreach ($stuff as $items) {
                    foreach ($items as $key => $item) {
                        if (isset($item['@attributes']['project-dir'])) {
                            $data[$item['@attributes']['project-dir']][$type][$key][] = $item;
                        }
                    }
                }
            }
        }

        return $data;
    }
}
