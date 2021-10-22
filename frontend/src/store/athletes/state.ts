export interface AthletesStateInterface {
  athlete: Athlete | null;
  newAthlete: Athlete | null;
  yearsArray: number[];
  searchedAthletes: Athlete[];
  favourites: Athlete[];
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
  requirement: string;
  unit: string;
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
    [key in Category]: string;
  };
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
}

export interface Year {
  current: number;
  allYears: number[];
}

function state(): AthletesStateInterface {
  return {
    athlete: null,
    newAthlete: null,
    yearsArray: [],
    searchedAthletes: [],
    favourites: [],
    searchString: '',
  };
}

export default state;
