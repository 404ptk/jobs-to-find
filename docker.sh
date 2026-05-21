#!/bin/bash

# JobsToFind Docker Helper Script
# Usage: ./docker.sh [command]

set -e

PROJECT_NAME="jobstofind"
DOCKER_COMPOSE="docker-compose"

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

print_help() {
    echo -e "${BLUE}JobsToFind - Docker Management${NC}"
    echo ""
    echo "Usage: ./docker.sh [command]"
    echo ""
    echo "Commands:"
    echo "  ${GREEN}up${NC}              - Start containers (build if needed)"
    echo "  ${GREEN}down${NC}            - Stop and remove containers"
    echo "  ${GREEN}build${NC}           - Build Docker image"
    echo "  ${GREEN}rebuild${NC}         - Rebuild Docker image from scratch"
    echo "  ${GREEN}logs${NC}            - Show application logs"
    echo "  ${GREEN}shell${NC}           - Open shell in app container"
    echo "  ${GREEN}tinker${NC}          - Open Laravel Tinker (REPL)"
    echo "  ${GREEN}migrate${NC}         - Run database migrations"
    echo "  ${GREEN}seed${NC}            - Run database seeders"
    echo "  ${GREEN}cache-clear${NC}     - Clear all caches"
    echo "  ${GREEN}test${NC}            - Run PHPUnit tests"
    echo "  ${GREEN}ps${NC}              - Show running containers"
    echo "  ${GREEN}stop${NC}            - Stop containers (don't remove)"
    echo "  ${GREEN}restart${NC}         - Restart containers"
    echo "  ${GREEN}clean${NC}           - Remove containers, volumes, and images"
    echo "  ${GREEN}help${NC}            - Show this help message"
    echo ""
}

case "$1" in
    up)
        echo -e "${BLUE}🚀 Starting containers...${NC}"
        $DOCKER_COMPOSE up -d
        echo -e "${GREEN}✅ Containers started!${NC}"
        echo -e "${YELLOW}📱 Application: http://localhost${NC}"
        ;;
    down)
        echo -e "${BLUE}🛑 Stopping containers...${NC}"
        $DOCKER_COMPOSE down
        echo -e "${GREEN}✅ Containers stopped!${NC}"
        ;;
    build)
        echo -e "${BLUE}🔨 Building Docker image...${NC}"
        $DOCKER_COMPOSE build
        echo -e "${GREEN}✅ Build complete!${NC}"
        ;;
    rebuild)
        echo -e "${BLUE}🔨 Rebuilding Docker image from scratch...${NC}"
        $DOCKER_COMPOSE build --no-cache
        echo -e "${GREEN}✅ Rebuild complete!${NC}"
        ;;
    logs)
        $DOCKER_COMPOSE logs -f app
        ;;
    shell)
        echo -e "${BLUE}📟 Opening shell in container...${NC}"
        $DOCKER_COMPOSE exec app sh
        ;;
    tinker)
        echo -e "${BLUE}🎵 Opening Laravel Tinker...${NC}"
        $DOCKER_COMPOSE exec app php artisan tinker
        ;;
    migrate)
        echo -e "${BLUE}🗄️ Running migrations...${NC}"
        $DOCKER_COMPOSE exec app php artisan migrate
        echo -e "${GREEN}✅ Migrations complete!${NC}"
        ;;
    seed)
        echo -e "${BLUE}🌱 Seeding database...${NC}"
        $DOCKER_COMPOSE exec app php artisan db:seed
        echo -e "${GREEN}✅ Seeding complete!${NC}"
        ;;
    cache-clear)
        echo -e "${BLUE}🧹 Clearing caches...${NC}"
        $DOCKER_COMPOSE exec app php artisan cache:clear
        $DOCKER_COMPOSE exec app php artisan config:clear
        $DOCKER_COMPOSE exec app php artisan view:clear
        $DOCKER_COMPOSE exec app php artisan route:clear
        echo -e "${GREEN}✅ Caches cleared!${NC}"
        ;;
    test)
        echo -e "${BLUE}🧪 Running tests...${NC}"
        $DOCKER_COMPOSE exec app php artisan test
        ;;
    ps)
        $DOCKER_COMPOSE ps
        ;;
    stop)
        echo -e "${BLUE}⏸️ Stopping containers...${NC}"
        $DOCKER_COMPOSE stop
        echo -e "${GREEN}✅ Containers stopped!${NC}"
        ;;
    restart)
        echo -e "${BLUE}🔄 Restarting containers...${NC}"
        $DOCKER_COMPOSE restart
        echo -e "${GREEN}✅ Containers restarted!${NC}"
        ;;
    clean)
        echo -e "${RED}⚠️  This will remove all containers, volumes, and images!${NC}"
        read -p "Are you sure? (y/n) " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            $DOCKER_COMPOSE down -v --rmi all
            echo -e "${GREEN}✅ Cleanup complete!${NC}"
        else
            echo -e "${YELLOW}Cancelled${NC}"
        fi
        ;;
    help|"")
        print_help
        ;;
    *)
        echo -e "${RED}Unknown command: $1${NC}"
        echo "Run './docker.sh help' for available commands"
        exit 1
        ;;
esac
