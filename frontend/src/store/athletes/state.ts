import { SessionStorage } from 'quasar';

export const CURRENT_YEAR_STORAGE_KEY = 'CURRENTYEARINSESSIONSTORAGE';
export const ALL_YEARS_STORAGE_KEY = 'ALLYEARSINSESSIONSTORAGE';
export const ATHLETE_STORAGE_KEY = 'CENTERATHLETEINSESSIONSTORAGE';
export const RELEVANT_ATHLETES_STORAGE_KEY =
  'ALLRELEVANTATHLETESINSESSIONSTORAGE';
export const FAVOURITES_STORAGE_KEY = 'FAVOURITESINSESSIONSTORAGE';

export interface AthletesStateInterface {
  athlete: Athlete | null;
  newAthlete: Athlete | null;
  allYearsArray: number[];
  currentYear: number;
  searchedAthletes: Athlete[];
  favourites: Athlete[];
  allRelevantAthletes: Athlete[];
  searchString: string;
}

export interface RequirementCollection {
  description: string;
  requirements: {
    bronze: Requirement;
    silver: Requirement;
    gold: Requirement;
  };
}

export interface Requirement {
  requirement_with_unit: string;
  requirement?: string;
  unit?: string;
  description?: string;
}

export interface PerformanceArray {
  performance: string;
  bronze_highlighted?: boolean;
  silver_highlighted?: boolean;
  gold_highlighted?: boolean;
}

export type Discipline = string;

export enum Category {
  coordination = 'coordination',
  endurance = 'endurance',
  speed = 'speed',
  strength = 'strength',
}

export interface Athlete {
  id: number;
  name: string;
  year: number;
  birthday: string | null;
  gender: string;
  notes: string;
  requirements_tag: string;
  medal_scores: {
    [key in Category]: {
      performance: string;
      points: number;
      discipline_name: string;
      value: string;
    };
  };
  hasFinished: boolean;
  canStillBeEdited: boolean;
  sportabzeichen_year_array: {
    lower_year: number;
    upper_year: number;
    tag: string;
    actual_sportabzeichen_age: number;
  };
  needed_requirements: {
    [key in Category]: { [key: Discipline]: RequirementCollection };
  };
  performances: {
    [key in Category]: { [key: Discipline]: PerformanceArray };
  };
  favourite: boolean;
  proofOfSwimming: number;
  proofOfSwimmingOk: boolean;
  lastBadgeYear: number;
  numberOfBadgesSoFar: number;
  lastYearIdentNo: string;
  newIdentNo: string;
}

export interface YearResource {
  current: number;
  allYears: number[];
}

let currentYear = -1;
let allYearsArray = [] as number[];
let athlete = null as Athlete | null;
let allRelevantAthletes = [] as Athlete[];
let favourites = [] as Athlete[];
// cache year stuff, to get smoother page refresh
try {
  const buf = SessionStorage.getItem<number>(CURRENT_YEAR_STORAGE_KEY);
  if (buf) {
    currentYear = buf;
  }
} catch (e) {}
try {
  const buf = SessionStorage.getItem<number[]>(ALL_YEARS_STORAGE_KEY);
  if (buf) {
    allYearsArray = buf;
  }
} catch (e) {}
try {
  const buf = SessionStorage.getItem<Athlete | 'null'>(ATHLETE_STORAGE_KEY);
  if (buf && buf != null && buf != 'null') {
    athlete = buf;
  }
} catch (e) {}
try {
  const buf = SessionStorage.getItem<Athlete[]>(RELEVANT_ATHLETES_STORAGE_KEY);
  if (buf && buf != null) {
    allRelevantAthletes = buf;
  }
} catch (e) {}
try {
  const buf = SessionStorage.getItem<Athlete[]>(FAVOURITES_STORAGE_KEY);
  if (buf && buf != null) {
    favourites = buf;
  }
} catch (e) {}

function state(): AthletesStateInterface {
  return {
    athlete: athlete,
    newAthlete: null,
    allYearsArray: allYearsArray,
    currentYear: currentYear,
    searchedAthletes: [],
    favourites: favourites,
    allRelevantAthletes: allRelevantAthletes,
    searchString: '',
  };
}

export default state;
